<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Redirect newly registered users to email verification page.
     */
    public function toResponse($request)
    {
        // 会員登録完了後はメール認証画面へリダイレクト
        // メール認証後は/setupにアクセスできるようになる
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Registration successful'], 201);
        }
        
        return redirect()->route('verification.notice');
    }
}

