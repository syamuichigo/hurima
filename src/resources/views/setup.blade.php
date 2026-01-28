@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/setup.css') }}">
@endsection

@section('content')
<div class="setup-container">
    <div class="setup-card">
        <h1 class="setup-title">プロフィール設定</h1>

        <form class="setup-form" method="POST" action="/setup_create" enctype="multipart/form-data">
            @csrf
            <div class="avatar-section">
                <div class="avatar-circle" id="avatar-preview"></div>
                <label for="avatar" class="avatar-button">画像を選択する</label>
                <input type="file" id="avatar" name="image" accept="image/*" class="avatar-input">
                @error('image')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <label class="field-label" for="name">ユーザー名</label>
            <input class="field-input" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="ユーザー名を入力">
            @error('name')
            <span class="error-message">{{ $message }}</span>
            @enderror

            <label class="field-label" for="postcode">郵便番号</label>
            <input class="field-input" type="text" id="postcode" name="postcode" value="{{ old('postcode') }}" placeholder="000-0000">
            @error('postcode')
            <span class="error-message">{{ $message }}</span>
            @enderror

            <label class="field-label" for="address">住所</label>
            <input class="field-input" type="text" id="address" name="address" value="{{ old('address') }}" placeholder="住所を入力">
            @error('address')
            <span class="error-message">{{ $message }}</span>
            @enderror

            <label class="field-label" for="building">建物名</label>
            <input class="field-input" type="text" id="building" name="building" value="{{ old('building') }}" placeholder="建物名を入力（任意）">
            @error('building')
            <span class="error-message">{{ $message }}</span>
            @enderror

            <button type="submit" class="submit-button">登録する</button>
        </form>
    </div>
</div>

<script>
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            preview.style.backgroundImage = 'url(' + e.target.result + ')';
            preview.style.backgroundSize = 'cover';
            preview.style.backgroundPosition = 'center';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection

