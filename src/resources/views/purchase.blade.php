@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="content-wrapper">
        <div class="left-section">
            <div class="product-image">
                <div class="image-placeholder">商品画像</div>
            </div>

            <div class="product-info">
                <h2 class="product-title">商品名</h2>
                <p class="product-price">¥ 47,000</p>
            </div>

            <div class="form-section">
                <h3 class="section-title">支払い方法</h3>
                <select class="select-box">
                    <option>選択してください</option>
                </select>
            </div>

            <div class="form-section">
                <div class="section-header">
                    <h3 class="section-title">配送先</h3>
                    <a href="#" class="change-link">変更する</a>
                </div>
                <p class="address-postal">〒 XXX-YYYY</p>
                <p class="address-detail">ここには住所と建物が入ります</p>
            </div>
        </div>

        <div class="right-section">
            <div class="summary-box">
                <div class="summary-row">
                    <span class="summary-label">商品代金</span>
                    <span class="summary-value">¥ 47,000</span>
                </div>
                <div class="summary-row payment-method">
                    <span class="summary-label">支払い方法</span>
                    <span class="summary-value">コンビニ払い</span>
                </div>
            </div>

            <button class="purchase-button">購入する</button>
        </div>
    </div>
</div>
@endsection