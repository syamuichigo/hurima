@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="content-wrapper">
        <form action="/purchase" method="POST">
            @csrf
            <input type="hidden" name="content_id" value="{{ $content->id }}">
            <input type="hidden" name="address" value="{{ $profile->address ?? '' }}">

            <div class="left-section">
                <div class="product-header">
                    <div class="product-image">
                        <div class="image-placeholder">
                            <img src="{{ asset($content->image) }}" alt="{{ $content->name }}">
                        </div>
                    </div>

                    <div class="product-info">
                        <h2 class="product-title">{{ $content->name }}</h2>
                        <p class="product-price">¥ {{ $content->price }}</p>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title"><label for="payment_method">支払い方法</label></h3>
                    <select id="payment_method" class="select-box payment-method-select" name="payment_method">
                        <option value="">選択してください</option>
                        <option value="コンビニ払い" {{ old('payment_method') == 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                        <option value="クレジットカード" {{ old('payment_method') == 'クレジットカード' ? 'selected' : '' }}>クレジットカード</option>
                    </select>
                    @error('payment_method')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-section">
                    <div class="section-header">
                        <h3 class="section-title">配送先</h3>
                        <a href="{{ url('/address') }}?content_id={{ $content->id }}" class="change-link">変更する</a>
                    </div>
                    @if($profile)
                    <p class="address-postal">〒 {{ $profile->postcode }}</p>
                    <p class="address-detail"> {{ $profile->address }} {{ $profile->building }}</p>
                    @else
                    <p class="address-detail">配送先が登録されていません</p>
                    @endif
                    @error('address')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="right-section">
                <div class="summary-box">
                    <div class="summary-row">
                        <span class="summary-label">商品代金</span>
                        <span class="summary-value">¥ {{ $content->price }}</span>
                    </div>
                    <div class="summary-row payment-method">
                        <span class="summary-label">支払い方法</span>
                        <span class="summary-value payment-method-display">{{ old('payment_method') ?: '選択してください' }}</span>
                    </div>
                </div>

                <button type="submit" class="purchase-button">購入する</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
document.querySelector('select[name="payment_method"]')?.addEventListener('change',function(e){document.querySelector('.payment-method-display').textContent=e.target.value||'選択してください'});
</script>
@endsection