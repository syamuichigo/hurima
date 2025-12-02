@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="form-card">
        <h1>会員登録</h1>

        <form method="POST" action="/register">
            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username">
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email">
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="password-confirm">確認用パスワード</label>
                <input type="password" id="password-confirm" name="password-confirm">
            </div>

            <button type="submit" class="submit-btn">登録する</button>
        </form>

        <div class="login-link">
            <a href="login">ログインはこちら</a>
        </div>
    </div>
</div>
@endsection