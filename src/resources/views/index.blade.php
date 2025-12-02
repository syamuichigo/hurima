@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section ('content')
<nav>
    <div class="nav-tabs">
        <a href="#" class="active">おすすめ</a>
        <a href="#">マイリスト</a>
    </div>
</nav>

<main>
    <div class="product-grid">
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
</main>
@endsection