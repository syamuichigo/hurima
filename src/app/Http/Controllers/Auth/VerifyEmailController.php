<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Create a new controller instance.
     * signedミドルウェアでURLの署名を検証してから実行される
     */
    public function __construct()
    {
        $this->middleware(['signed', 'throttle:6,1']);
    }

    /**
     * メール認証を完了する（未ログインでも署名付きURLで認証可能）
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect('/login')->with('error', 'ユーザーが見つかりません。');
        }

        // ハッシュの検証（メールアドレスの一致を確認）
        if (!hash_equals(sha1($user->getEmailForVerification()), (string) $request->route('hash'))) {
            return redirect('/login')->with('error', '認証リンクが無効です。');
        }

        // ログインしていない場合はログインしてから認証
        if (!auth()->check()) {
            auth()->login($user);
        } else {
            // ログイン済みの場合、別ユーザーのリンクの場合は403ではなくログイン画面へ
            if (auth()->id() !== (int) $request->route('id')) {
                auth()->logout();
                auth()->login($user);
            }
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('info', 'すでにメール認証が完了しています');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // プロフィールが既に登録されている場合はトップページへ
        if (Profile::where('user_id', $user->id)->exists()) {
            return redirect('/')->with('success', 'メール認証が完了しました');
        }

        return redirect('/setup')->with('success', 'メール認証が完了しました');
    }
}
