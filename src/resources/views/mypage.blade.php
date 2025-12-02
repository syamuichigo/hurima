@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="profile-section">
        <div class="avatar"></div>
        <h1 class="username">ユーザー名</h1>
        <button class="edit-button">プロフィールを編集</button>
    </div>

    <div class="tabs">
        <div class="tab active">出品した商品</div>
        <div class="tab">購入した商品</div>
    </div>

    <div class="products-grid">
        <div class="product-card">
            <div class="product-image">商品画像</div>
            <div class="product-name">商品名</div>
        </div>
        <div class="product-card">
            <div class="product-image">商品画像</div>
            <div class="product-name">商品名</div>
        </div>
        <div class="product-card">
            <div class="product-image">商品画像</div>
            <div class="product-name">商品名</div>
        </div>
        <div class="product-card">
            <div class="product-image">商品画像</div>
            <div class="product-name">商品名</div>
        </div>
        <div class="product-card">
            <div class="product-image">商品画像</div>
            <div class="product-name">商品名</div>
        </div>
        <div class="product-card">
            <div class="product-image">商品画像</div>
            <div class="product-name">商品名</div>
        </div>
        <div class="product-card">
            <div class="product-image">商品画像</div>
            <div class="product-name">商品名</div>
        </div>
    </div>
</div>
@endsection