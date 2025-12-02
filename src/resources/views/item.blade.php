@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="product-section">
        <div class="product-image">
            <div class="image-placeholder">商品画像</div>
        </div>

        <div class="product-info">
            <h1 class="product-title">商品名がここに入る</h1>
            <p class="product-number">NO.47000</p>
            <p class="product-price">¥47,000 <span class="tax-label">(税込)</span></p>

            <div class="action-buttons">
                <button class="icon-btn">♡</button>
                <button class="icon-btn">💬</button>
            </div>

            <button class="purchase-btn">購入手続きへ</button>

            <div class="description-section">
                <h2 class="section-title">商品説明</h2>
                <p class="description-text">カラー：グレー</p>
                <p class="description-text">完全<br>オーダー発注製品です。個名彫り承ります。<br>購入後、正確な仕上げについては、</p>
            </div>

            <div class="details-section">
                <h2 class="section-title">商品の情報</h2>
                <div class="detail-row">
                    <span class="detail-label">カテゴリー</span>
                    <span class="detail-value">
                        <span class="tag">本革</span>
                        <span class="tag">メンズ</span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">商品の状態</span>
                    <span class="detail-value">新品</span>
                </div>
            </div>

            <div class="comments-section">
                <h2 class="section-title">コメント(1)</h2>

                <div class="comment">
                    <div class="comment-avatar">admin</div>
                    <p class="comment-text">こちらにコメントが入ります。</p>
                </div>

                <div class="comment-form">
                    <h3 class="form-title">商品へのコメント</h3>
                    <textarea class="comment-textarea"></textarea>
                    <button class="submit-btn">コメントを送信する</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection