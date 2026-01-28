@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-email-container">
    <div class="verify-email-content">
        @if (session('status') == 'verification-link-sent')
            <div class="success-message">
                新しい認証リンクをメールアドレスに送信しました。
            </div>
        @endif

        <div class="verify-email-message">
            <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
            <p>メール認証を完了してください。</p>
        </div>

        <div class="verify-button-container">
            <form method="POST" action="{{ route('email.verify.complete') }}" style="display: inline;">
                @csrf
                <button type="submit" class="verify-button">認証はこちらから</button>
            </form>
        </div>

        <div class="resend-link">
            <form method="POST" action="{{ route('verification.send') }}" style="display: inline;">
                @csrf
                <button type="submit">認証メールを再送する</button>
            </form>
        </div>
    </div>
</div>
@endsection

