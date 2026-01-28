<?php

namespace App\Http\Responses;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Redirect users after login.
     * If email is not verified, redirect to email verification page.
     */
    public function toResponse($request)
    {
        $user = Auth::user();
        
        // メール認証が完了していない場合はメール認証画面へ
        if ($user && !$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        
        // メール認証が完了している場合はホームへ
        return redirect(RouteServiceProvider::HOME);
    }
}

