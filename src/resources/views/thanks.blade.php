@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="thanks-wrapper">
        <div class="thanks-message">
            <h1 class="thanks-title">ご購入ありがとうございました！</h1>
            <p class="thanks-text">商品の準備が整い次第、発送させていただきます。</p>
        </div>
        <div class="button-group">
            <a href="/" class="back-button">商品一覧に戻る</a>
            <a href="/mypage" class="mypage-button">マイページへ</a>
        </div>
    </div>
</div>
@endsection

