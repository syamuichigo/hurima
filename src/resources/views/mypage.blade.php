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
                @php
                    $profileImage = optional($user->profile)->image;
                    if ($profileImage && strpos($profileImage, 'storage/') !== 0 && strpos($profileImage, 'images/') !== 0) {
                        $profileImage = 'storage/' . $profileImage;
                    }
                @endphp
                <img src="{{ asset($profileImage) }}" alt="プロフィール画像">
            @endif
        </div>
        <div class="profile-info">
            <h1 class="username">
                {{ $user->profile->name ?? $user->name }}
            </h1>
            @if(isset($avgRating) && $avgRating !== null)
            <div class="average-rating">
                @php
                    $filled = min(5, max(0, (int) round($avgRating)));
                    $empty = 5 - $filled;
                @endphp
                <span class="rating-stars">{{ str_repeat('★', $filled) }}{{ str_repeat('☆', $empty) }}</span>
            </div>
            @endif
        </div>
        <form method="POST" action="/profile" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $user->id }}">
            <button type="submit" class="edit-button">プロフィールを編集</button>
        </form>
    </div>

    <input type="radio" name="mypage_tab" id="tab-selling" class="mypage-tab-radio" checked>
    <input type="radio" name="mypage_tab" id="tab-transacting" class="mypage-tab-radio">
    <input type="radio" name="mypage_tab" id="tab-bought" class="mypage-tab-radio">

    <div class="tabs">
        <label for="tab-selling" class="tab-label">出品した商品</label>
        <label for="tab-transacting" class="tab-label">
            取引中
            @if(isset($transactingBadgeCount) && $transactingBadgeCount > 0)
            <span class="tab-badge">{{ $transactingBadgeCount }}</span>
            @endif
        </label>
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

            {{-- 取引中パネル --}}
            <div class="mypage-slider-panel" id="transacting-panel">
                <div class="product-grid">
                    @if ($transactions->isEmpty())
                        <div class="empty-message">取引中の商品がありません</div>
                    @else
                        @foreach ($transactions as $tx)
                        <a href="/transaction-chat/{{ $tx->id }}" class="product-card transaction-card">
                            <div class="product-image-wrapper">
                                @php
                                    $txImage = optional($tx->content)->image ?? $tx->image ?? null;
                                @endphp
                                @if($txImage)
                                    <img src="{{ asset($txImage) }}" alt="{{ $tx->content->name ?? $tx->name ?? '商品' }}" class="product-image">
                                @else
                                    <div class="product-image-placeholder">商品画像</div>
                                @endif
                                @if(isset($unreadByPurchase) && ($unreadByPurchase[$tx->id] ?? 0) > 0)
                                <span class="product-notification-badge">{{ $unreadByPurchase[$tx->id] }}</span>
                                @endif
                                <span class="transaction-role-badge {{ $tx->is_buyer ? 'role-buyer' : 'role-seller' }}">
                                    {{ $tx->is_buyer ? '購入' : '出品' }}
                                </span>
                            </div>
                            <div class="product-name">
                                {{ $tx->content->name ?? $tx->name ?? '商品名' }}
                            </div>
                        </a>
                        @endforeach
                    @endif
                </div>
                @if (!$transactions->isEmpty())
                <div class="pagination-wrapper">
                    {{ $transactions->links() }}
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
                        @php
                            $purchaseContent = optional($purchase->content);
                            $purchaseImage = $purchaseContent->image ?? $purchase->image ?? null;
                            $purchaseName = $purchaseContent->name ?? $purchase->name ?? '商品名';
                            $itemLink = $purchase->content_id ? "/item/{$purchase->content_id}" : "#";
                        @endphp
                        <a href="{{ $itemLink }}" class="product-card">
                            <div class="product-image-wrapper">
                                @if($purchaseImage)
                                <img src="{{ asset($purchaseImage) }}" alt="{{ $purchaseName }}" class="product-image">
                                @else
                                <div class="product-image-placeholder">商品画像</div>
                                @endif
                            </div>
                            <div class="product-name">{{ $purchaseName }}</div>
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