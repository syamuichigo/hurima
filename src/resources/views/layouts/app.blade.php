<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <a href="/" class="logo-link">
                    <img src="{{ asset('storage/image/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECHヘッダーロゴ" class="logo-img">
                </a>
            </div>
            @unless(request()->is('register') || request()->is('login') || request()->is('login1'))
            <div class="search-box">
                <form method="GET" action="/search" class="search-form">
                    <input type="text" name="q" placeholder="なにをお探しですか？" class="search-input" value="{{ request('q') }}">
                    <button type="submit" class="search-button" style="display: none;">検索</button>
                </form>
            </div>
            @endunless
            @unless(request()->is('register') || request()->is('login') || request()->is('login1'))
            <nav class="header-nav">
                @auth
                <form method="POST" action="/logout" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-link">ログアウト</button>
                </form>
                <a href="/mypage" class="nav-link">マイページ</a>
                <a href="/sell" class="nav-button">出品</a>
                @else
                <a href="/login" class="nav-link">ログイン</a>
                <a href="/sell" class="nav-button">出品</a>
                @endauth
            </nav>
            @endunless
        </div>
    </header>

    @if (session('error'))
    <input type="checkbox" id="error-popup-toggle" class="popup-toggle" checked>
    <label for="error-popup-toggle" class="popup-overlay"></label>
    <div class="popup-modal">
        <p class="popup-message">{{ session('error') }}</p>
    </div>
    @endif

    @yield('content')
    @yield('js')
</body>

</html>