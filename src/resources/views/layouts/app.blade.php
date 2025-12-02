<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="logo">
            <img src="{{ asset('storage/image/CT.png') }}" alt="CT">
            <img src="{{ asset('storage/image/C.png') }}" alt="C">
            <img src="{{ asset('storage/image/O.png') }}" alt="O">
            <img src="{{ asset('storage/image/A.png') }}" alt="A">
            <img src="{{ asset('storage/image/C.png') }}" alt="C">
            <img src="{{ asset('storage/image/H.png') }}" alt="H">
            <img src="{{ asset('storage/image/T.png') }}" alt="T">
            <img src="{{ asset('storage/image/E.png') }}" alt="E">
            <img src="{{ asset('storage/image/C.png') }}" alt="C">
            <img src="{{ asset('storage/image/H.png') }}" alt="H">
        </div>
    </header>

    @yield('content')

</body>

</html>