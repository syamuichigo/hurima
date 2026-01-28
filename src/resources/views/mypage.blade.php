@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="container">
    @foreach ($users as $user)
    <div class="profile-section">
        <div class="avatar">
            @if(!empty(optional($user->profile)->image))
                <img src="{{ asset($user->profile->image) }}" alt="プロフィール画像">
            @endif
        </div>
        <h1 class="username">
            {{ $user->profile->name ?? $user->name }}
        </h1>
        <form method="POST" action="/profile" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $user->id }}">
            <button type="submit" class="edit-button">プロフィールを編集</button>
        </form>
    </div>

    <input type="radio" name="mypage_tab" id="tab-selling" class="mypage-tab-radio" checked>
    <input type="radio" name="mypage_tab" id="tab-bought" class="mypage-tab-radio">

    <div class="tabs">
        <label for="tab-selling" class="tab-label">出品した商品</label>
        <label for="tab-bought" class="tab-label">購入した商品</label>
    </div>

    <div class="mypage-slider-container">
        <div class="mypage-slider-wrapper">
            {{-- 出品した商品パネル --}}
            <div class="mypage-slider-panel" id="selling-panel">
                <div class="product-grid">
                    @if ($listings->isEmpty())
                        <div class="empty-message">出品した商品がありません</div>
                    @else
                        @foreach ($listings as $listing)
                        <a href="/item/{{ $listing->content->id }}" class="product-card">
                            <div class="product-image-wrapper">
                                <img src="{{ asset($listing->content->image) }}" alt="{{ $listing->content->name }}" class="product-image">
                                @if($listing->content->is_sold ?? false)
                                <div class="sold-badge">SOLD</div>
                                @endif
                            </div>
                            <div class="product-name">{{ $listing->content->name }}</div>
                        </a>
                        @endforeach
                    @endif
                </div>
                {{-- 出品した商品のページネーション --}}
                @if (!$listings->isEmpty())
                <div class="pagination-wrapper">
                    {{ $listings->links() }}
                </div>
                @endif
            </div>

            {{-- 購入した商品パネル --}}
            <div class="mypage-slider-panel" id="bought-panel">
                <div class="product-grid">
                    @if ($purchases->isEmpty())
                        <div class="empty-message">購入した商品がありません</div>
                    @else
                        @foreach ($purchases as $purchase)
                        <a href="/item/{{ $purchase->id }}" class="product-card">
                            <img src="{{ asset($purchase->image) }}" alt="{{ $purchase->name }}" class="product-image">
                            <div class="product-name">{{ $purchase->name }}</div>
                        </a>
                        @endforeach
                    @endif
                </div>
                {{-- 購入した商品のページネーション --}}
                @if (!$purchases->isEmpty())
                <div class="pagination-wrapper">
                    {{ $purchases->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection