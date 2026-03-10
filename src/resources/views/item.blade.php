@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="container">
    @foreach ($content as $content)
    <div class="product-section">
        <div class="product-image">
            <div class="product-image-wrapper">
                <img src="{{ asset($content->image) }}" alt="{{ $content->name }}" class="image-placeholder">
                @if($content->is_sold ?? false)
                <div class="sold-badge">SOLD</div>
                @endif
            </div>
        </div>

        <div class="product-info">
            <h1 class="product-title">{{ $content->name }}</h1>
            <p class="product-brand">{{ $content->brand }}</p>
            <p class="product-price">¥{{ $content->price }} <span class="tax-label">(税込)</span></p>

            <div class="action-buttons">
                <div class="action-item">
                    @auth
                    <form action="/favorite/toggle" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="content_id" value="{{ $content->id }}">
                        <button type="submit" class="icon-btn favorite-btn">
                            <img src="{{ asset($content->is_favorited ? 'images/ハートロゴ_ピンク.png' : 'images/ハートロゴ_デフォルト.png') }}" alt="♡" class="favorite-icon">
                        </button>
                    </form>
                    @else
                    <label for="login-popup-toggle" class="icon-btn favorite-btn-guest">
                        <img src="{{ asset('images/ハートロゴ_デフォルト.png') }}" alt="♡">
                    </label>
                    @endauth
                    <span class="action-count favorite-count">{{ $content->favorite_count ?? 0 }}</span>
                </div>
                <div class="action-item">
                    <a href="#comments-section" class="icon-btn comment-display">
                        <img src="{{ asset('storage/image/ふきだしロゴ.png') }}" alt="💬">
                    </a>
                    <span class="action-count comment-count">{{ $content->comment_count ?? 0 }}</span>
                </div>
            </div>

            @if($content->is_sold ?? false)
            <button type="button" class="purchase-btn sold-btn" disabled>SOLD</button>
            @else
            <form action="/purchase" method="GET">
                <input type="hidden" name="content_id" value="{{ $content->id }}">
                <button type="submit" class="purchase-btn">購入手続きへ</button>
            </form>
            @endif

            <div class="description-section">
                <h2 class="section-title">商品説明</h2>
                <p class="description-text">{{ $content->info }}</p>
            </div>

            <div class="details-section">
                <h2 class="section-title">商品の情報</h2>
                <div class="detail-row">
                    <span class="detail-label">カテゴリー</span>
                    <span class="detail-value">
                        @if($content->categories && $content->categories->isNotEmpty())
                            @foreach($content->categories as $category)
                                <span class="tag">{{ $category->name }}</span>
                            @endforeach
                        @elseif($content->category)
                            <span class="tag">{{ $content->category->name }}</span>
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">商品の状態</span>
                    <span class="detail-value">
                        @if($content->condition)
                            {{ $content->condition->name }}
                        @endif
                    </span>
                </div>
            </div>

            <div class="comments-section" id="comments-section">
                <h2 class="section-title">
                    コメント
                    <span class="comment-count-inline">({{ $content->comment_count ?? 0 }})</span>
                </h2>

                <div class="comments-list">
                    @forelse ($content->comments as $comment)
                        <div class="comment">
                            <div class="comment-header">
                                <div class="comment-user-icon">
                                    @if (optional($comment->user->profile)->image)
                                        @php
                                            $profileImage = optional($comment->user->profile)->image;
                                            if ($profileImage && strpos($profileImage, 'storage/') !== 0 && strpos($profileImage, 'images/') !== 0) {
                                                $profileImage = 'storage/' . $profileImage;
                                            }
                                        @endphp
                                        <img src="{{ asset($profileImage) }}" alt="ユーザーアイコン">
                                    @else
                                        <span>U</span>
                                    @endif
                                </div>
                                <div class="comment-user-name">
                                    {{ optional($comment->user->profile)->name ?? $comment->user->username }}
                                </div>
                            </div>
                            <p class="comment-text">{{ $comment->comment }}</p>
                        </div>
                    @empty
                        <p class="no-comments">まだコメントはありません。</p>
                    @endforelse
                </div>

                <div class="comment-form">
                    <h3 class="form-title">商品へのコメント</h3>
                    <form action="/comment/store" method="POST">
                        @csrf
                        <input type="hidden" name="content_id" value="{{ $content->id }}">
                        <textarea id="comment" class="comment-textarea" name="comment">{{ old('comment') }}</textarea>
                        @error('comment')
                        <span class="error-message">{{ $message }}</span>
                        @enderror
                        @auth
                        <button type="submit" class="submit-btn">コメントを送信する</button>
                        @else
                        <label for="login-popup-toggle" class="submit-btn" style="display: block; text-align: center; cursor: pointer;">コメントを送信する</label>
                        @endauth
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- ログインポップアップ（未ログイン時のお気に入りクリック用） -->
<input type="checkbox" id="login-popup-toggle" class="popup-toggle">
<label for="login-popup-toggle" class="popup-overlay"></label>
<div class="popup-modal">
    <p class="popup-message">ログインしてください</p>
</div>
