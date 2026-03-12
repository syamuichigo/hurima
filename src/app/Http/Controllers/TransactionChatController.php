<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Purchase;
use App\Models\TransactionMessage;
use App\Models\TransactionRating;
use App\Models\User;
use App\Models\TransactionNotification;
use App\Mail\RatingReceivedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $isSeller = $sellerId === $currentUserId;
        $otherUserId = $isBuyer ? $sellerId : $purchase->user_id;
        $otherUser = $otherUserId ? User::with('profile')->find($otherUserId) : null;
        $ratedUserId = $otherUserId;

        $messages = TransactionMessage::with('user.profile')
            ->where('purchase_id', $purchase->id)
            ->orderBy('created_at')
            ->get();

        // この取引の未読通知を既読にする
        TransactionNotification::where('user_id', $currentUserId)
            ->where('purchase_id', $purchase->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $otherTransactions = $this->userTransactionsQuery($currentUserId)
            ->where('id', '!=', $purchase->id)
            ->where('status', '!=', '完了')
            ->get();

        $currentUserHasRated = TransactionRating::where('purchase_id', $purchase->id)
            ->where('rater_user_id', $currentUserId)
            ->exists();
        $buyerHasRated = TransactionRating::where('purchase_id', $purchase->id)
            ->where('rater_user_id', $purchase->user_id)
            ->exists();
        $buyerCanComplete = $isBuyer && !$currentUserHasRated;
        $sellerCanComplete = $isSeller && !$currentUserHasRated;
        // 出品者が購入者の評価送信後にチャットを開いたときのみモーダルを自動表示
        $shouldAutoOpenRatingModal = $sellerCanComplete && $buyerHasRated;

        return view('transaction-chat', compact(
            'purchase',
            'messages',
            'otherTransactions',
            'otherUser',
            'ratedUserId',
            'isBuyer',
            'buyerCanComplete',
            'sellerCanComplete',
            'shouldAutoOpenRatingModal'
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

        // 送信先（相手）に新着メッセージ通知を作成
        $sellerId = $this->getSellerId($purchase);
        $recipientId = (auth()->id() === $purchase->user_id) ? $sellerId : $purchase->user_id;
        if ($recipientId) {
            TransactionNotification::create([
                'user_id' => $recipientId,
                'purchase_id' => $purchase->id,
                'type' => 'new_message',
            ]);
        }

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

    private function bothPartiesRated(Purchase $purchase): bool
    {
        $sellerId = $this->getSellerId($purchase);
        $buyerId = $purchase->user_id;
        if (!$sellerId || !$buyerId) {
            return false;
        }
        $buyerRated = TransactionRating::where('purchase_id', $purchase->id)
            ->where('rater_user_id', $buyerId)
            ->exists();
        $sellerRated = TransactionRating::where('purchase_id', $purchase->id)
            ->where('rater_user_id', $sellerId)
            ->exists();
        return $buyerRated && $sellerRated;
    }

    public function storeRating(Request $request, $purchaseId)
    {
        $purchase = Purchase::findOrFail($purchaseId);
        $sellerId = $this->ensureUserCanAccess($purchase);
        $currentUserId = auth()->id();

        $isBuyer = $purchase->user_id === $currentUserId;
        $isSeller = $sellerId === $currentUserId;
        if (!$isBuyer && !$isSeller) {
            return redirect()->back()->with('error', 'この取引の評価権限がありません');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'rated_user_id' => 'required|integer|exists:users,id',
        ]);

        $ratedUserId = $isBuyer ? $sellerId : $purchase->user_id;
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

        // 購入者が出品者を評価した場合のみ通知・メール送信（出品者が購入者を評価した場合は通知しない）
        if ($isBuyer) {
            TransactionNotification::create([
                'user_id' => $ratedUserId,
                'purchase_id' => $purchase->id,
                'type' => 'rating_received',
            ]);

            $ratedUser = User::find($ratedUserId);
            $raterUser = User::find($currentUserId);
            if ($ratedUser && $raterUser) {
                Mail::to($ratedUser->email)->send(new RatingReceivedMail($ratedUser, $raterUser, (int)$request->input('rating')));
            }
        }

        if ($this->bothPartiesRated($purchase)) {
            $purchase->status = '完了';
            $purchase->save();
        }

        return redirect('/');
    }
}
