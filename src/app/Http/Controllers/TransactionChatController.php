<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Purchase;
use App\Models\TransactionMessage;
use App\Models\TransactionRating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransactionChatController extends Controller
{
    private function getSellerId(Purchase $purchase)
    {
        if (!$purchase->content_id) {
            return null;
        }

        return Listing::where('content_id', $purchase->content_id)->value('user_id');
    }

    private function ensureUserCanAccess(Purchase $purchase)
    {
        $sellerId = $this->getSellerId($purchase);
        $currentUserId = auth()->id();

        if ($purchase->user_id !== $currentUserId && $sellerId !== $currentUserId) {
            abort(403, 'アクセス権限がありません');
        }

        return $sellerId;
    }

    private function userTransactionsQuery($userId)
    {
        $sellerContentIds = Listing::where('user_id', $userId)->pluck('content_id');

        return Purchase::where('user_id', $userId)
            ->orWhereIn('content_id', $sellerContentIds)
            ->with('content')
            ->orderByDesc('created_at');
    }

    public function show($purchaseId)
    {
        $purchase = Purchase::with('content')->findOrFail($purchaseId);
        $sellerId = $this->ensureUserCanAccess($purchase);
        $currentUserId = auth()->id();

        $isBuyer = $purchase->user_id === $currentUserId;
        $otherUserId = $isBuyer ? $sellerId : $purchase->user_id;
        $otherUser = $otherUserId ? User::with('profile')->find($otherUserId) : null;
        $ratedUserId = $otherUserId;

        $messages = TransactionMessage::with('user.profile')
            ->where('purchase_id', $purchase->id)
            ->orderBy('created_at')
            ->get();

        $otherTransactions = $this->userTransactionsQuery($currentUserId)
            ->where('id', '!=', $purchase->id)
            ->get();

        $buyerCanComplete = $isBuyer && !TransactionRating::where('purchase_id', $purchase->id)
            ->where('rater_user_id', $currentUserId)
            ->exists();

        $shouldShowRatingModal = false;

        return view('transaction-chat', compact(
            'purchase',
            'messages',
            'otherTransactions',
            'otherUser',
            'ratedUserId',
            'isBuyer',
            'buyerCanComplete',
            'shouldShowRatingModal'
        ));
    }

    public function storeMessage(Request $request, $purchaseId)
    {
        $purchase = Purchase::findOrFail($purchaseId);
        $this->ensureUserCanAccess($purchase);

        $request->validate([
            'message' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);

        $hasMessage = $request->filled('message');
        $hasImage = $request->hasFile('image');
        if (!$hasMessage && !$hasImage) {
            return redirect()->back()->with('error', 'メッセージか画像のどちらかを入力してください');
        }

        $imagePath = null;
        if ($hasImage) {
            $imagePath = $request->file('image')->store('transaction-messages', 'public');
        }

        TransactionMessage::create([
            'purchase_id' => $purchase->id,
            'user_id' => auth()->id(),
            'message' => $request->input('message'),
            'image' => $imagePath,
        ]);

        return redirect()->route('transaction.chat', ['purchaseId' => $purchase->id]);
    }

    public function updateMessage(Request $request, $purchaseId, $messageId)
    {
        $purchase = Purchase::findOrFail($purchaseId);
        $this->ensureUserCanAccess($purchase);

        $message = TransactionMessage::where('purchase_id', $purchase->id)
            ->where('id', $messageId)
            ->firstOrFail();

        if ($message->user_id !== auth()->id()) {
            abort(403, '編集権限がありません');
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message->message = $request->input('message');
        $message->save();

        return redirect()->route('transaction.chat', ['purchaseId' => $purchase->id]);
    }

    public function deleteMessage($purchaseId, $messageId)
    {
        $purchase = Purchase::findOrFail($purchaseId);
        $this->ensureUserCanAccess($purchase);

        $message = TransactionMessage::where('purchase_id', $purchase->id)
            ->where('id', $messageId)
            ->firstOrFail();

        if ($message->user_id !== auth()->id()) {
            abort(403, '削除権限がありません');
        }

        if ($message->image) {
            Storage::disk('public')->delete($message->image);
        }

        $message->delete();

        return redirect()->route('transaction.chat', ['purchaseId' => $purchase->id]);
    }

    public function storeRating(Request $request, $purchaseId)
    {
        $purchase = Purchase::findOrFail($purchaseId);
        $sellerId = $this->ensureUserCanAccess($purchase);
        $currentUserId = auth()->id();

        $isBuyer = $purchase->user_id === $currentUserId;
        if (!$isBuyer) {
            return redirect()->back()->with('error', '評価は購入者のみ送信できます');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'rated_user_id' => 'required|integer|exists:users,id',
        ]);

        $ratedUserId = $sellerId;
        if (!$ratedUserId || (int)$request->input('rated_user_id') !== (int)$ratedUserId) {
            return redirect()->back()->with('error', '評価対象のユーザー情報が正しくありません');
        }

        $alreadyRated = TransactionRating::where('purchase_id', $purchase->id)
            ->where('rater_user_id', $currentUserId)
            ->exists();
        if ($alreadyRated) {
            return redirect()->back()->with('error', 'すでに評価済みです');
        }

        TransactionRating::create([
            'purchase_id' => $purchase->id,
            'rater_user_id' => $currentUserId,
            'rated_user_id' => $ratedUserId,
            'rating' => (int)$request->input('rating'),
        ]);

        $purchase->status = '完了';
        $purchase->save();

        return redirect()->route('transaction.chat', ['purchaseId' => $purchase->id])
            ->with('success', '評価を送信しました');
    }
}
