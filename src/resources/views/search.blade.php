@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<input type="radio" name="index_tab" id="tab-recommended" class="tab-radio" checked>
<input type="radio" name="index_tab" id="tab-mylist" class="tab-radio">

<nav class="sub-nav">
    <div class="nav-tabs">
        <label for="tab-recommended" class="tab-label">おすすめ</label>
        <label for="tab-mylist" class="tab-label">マイリスト</label>
    </div>
</nav>

<main>
    <div class="slider-container">
        <div class="slider-wrapper">
            <!-- おすすめセクション（検索結果） -->
            <div class="slider-panel active" id="recommended">
                <div class="search-results-header">
                    <h2 class="search-title">検索結果: "{{ $query }}"</h2>
                    <p class="search-count">{{ $contents->total() }}件の商品が見つかりました</p>
                </div>
                @if($contents->count() > 0)
                <div class="product-grid">
                    @foreach ($contents as $content)
                    <a href="/item/{{ $content->id }}" class="product-card">
                        <div class="product-image-wrapper">
                            <img src="{{ asset($content->image) }}" alt="{{ $content->name }}" class="product-image">
                            @if($content->is_sold ?? false)
                            <div class="sold-badge">SOLD</div>
                            @endif
                        </div>
                        <div class="product-name">{{ $content->name }}</div>
                    </a>
                    @endforeach
                </div>
                {{-- ページネーションリンク --}}
                <div class="pagination-wrapper">
                    {{ $contents->appends(['q' => $query])->links() }}
                </div>
                @else
                <div class="empty-message">
                    <p>検索条件に一致する商品が見つかりませんでした。</p>
                    <p>別のキーワードで検索してみてください。</p>
                </div>
                @endif
            </div>
            
            <!-- マイリストセクション（検索結果） -->
            <div class="slider-panel" id="mylist">
                <div class="search-results-header">
                    <h2 class="search-title">検索結果: "{{ $query }}"</h2>
                    @auth
                        @if(isset($myContents) && count($myContents) > 0)
                            <p class="search-count">マイリストから{{ $myContents->total() }}件の商品が見つかりました</p>
                        @else
                            <p class="search-count">マイリストに検索条件に一致する商品がありません</p>
                        @endif
                    @else
                        <p class="search-count">ログインするとマイリストの商品が表示されます</p>
                    @endauth
                </div>
                <div class="product-grid">
                    @auth
                        @if(isset($myContents) && count($myContents) > 0)
                            @foreach ($myContents as $content)
                            <a href="/item/{{ $content->id }}" class="product-card">
                                <div class="product-image-wrapper">
                                    <img src="{{ asset($content->image) }}" alt="{{ $content->name }}" class="product-image">
                                    @if($content->is_sold ?? false)
                                    <div class="sold-badge">SOLD</div>
                                    @endif
                                </div>
                                <div class="product-name">{{ $content->name }}</div>
                            </a>
                            @endforeach
                            {{-- マイリストのページネーションリンク --}}
                            <div class="pagination-wrapper">
                                {{ $myContents->appends(['q' => $query])->links() }}
                            </div>
                        @else
                            <div class="empty-message">マイリストに検索条件に一致する商品がありません</div>
                        @endif
                    @else
                        <div class="empty-message">ログインするとマイリストの商品が表示されます</div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

