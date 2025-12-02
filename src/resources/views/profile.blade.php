@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <h1 class="profile-title">プロフィール設定</h1>

    <div class="profile-image-section">
        <div class="profile-image-placeholder"></div>
        <button class="image-upload-btn">画像を選択する</button>
    </div>

    <form class="profile-form">
        <div class="form-group">
            <label class="form-label">ユーザー名</label>
            <input type="text" class="form-input" placeholder="既存の値が入力されている">
        </div>

        <div class="form-group">
            <label class="form-label">郵便番号</label>
            <input type="text" class="form-input" placeholder="既存の値が入力されている">
        </div>

        <div class="form-group">
            <label class="form-label">住所</label>
            <input type="text" class="form-input" placeholder="既存の値が入力されている">
        </div>

        <div class="form-group">
            <label class="form-label">建物名</label>
            <input type="text" class="form-input" placeholder="既存の値が入力されている">
        </div>

        <button type="submit" class="submit-btn">更新する</button>
    </form>
</div>
@endsection