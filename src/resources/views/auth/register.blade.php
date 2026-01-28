@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="form-card">
        <h1>会員登録</h1>

        <form method="POST" action="/register">
            @csrf
            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username">
                @error('username')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email">
                @error('email')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <div class="password-input-wrapper">
                    <input type="password" id="password" name="password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <span class="password-toggle-icon">👁</span>
                    </button>
                </div>
                @error('password')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm">確認用パスワード</label>
                <div class="password-input-wrapper">
                    <input type="password" id="password-confirm" name="password_confirmation">
                    <button type="button" class="password-toggle" onclick="togglePassword('password-confirm')">
                        <span class="password-toggle-icon">👁</span>
                    </button>
                </div>
                @error('password_confirmation')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="submit-btn">登録する</button>
        </form>

        <div class="login-link">
            <a href="/login">ログインはこちら</a>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.parentElement.querySelector('.password-toggle-icon');
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.textContent = input.type === 'password' ? '👁' : '👁️‍🗨️';
}
</script>
@endsection