@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <h1 class="profile-title">プロフィール設定</h1>

    @foreach ($users as $user)
    <form class="profile-form" method="POST" action="/profile-update" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <div class="profile-image-section">
            <div id="profile-image-preview">
                @if(optional($user->profile)->image)
                    <img src="{{ asset($user->profile->image) }}" alt="プロフィール画像" id="profile-image">
                @else
                    <div class="profile-image-placeholder" id="profile-image-placeholder"></div>
                @endif
            </div>
            <input type="file" id="avatar" name="image" accept="image/*" class="avatar-input" style="display: none;">
            <label for="avatar" class="image-upload-btn">画像を選択する</label>
            @error('image')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">ユーザー名</label>
            <input type="text" class="form-input" name="name" value="{{ old('name', optional($user->profile)->name) }}">
            @error('name')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">郵便番号</label>
            <input type="text" class="form-input" name="postcode" value="{{ old('postcode', optional($user->profile)->postcode) }}">
            @error('postcode')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">住所</label>
            <input type="text" class="form-input" name="address" value="{{ old('address', optional($user->profile)->address) }}">
            @error('address')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">建物名</label>
            <input type="text" class="form-input" name="building" value="{{ old('building', optional($user->profile)->building) }}">
            @error('building')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="submit-btn">更新する</button>
    </form>
    @endforeach
</div>

<script>
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profile-image-preview');
            const existingImg = preview.querySelector('img');
            const placeholder = preview.querySelector('#profile-image-placeholder');
            
            if (existingImg) {
                existingImg.src = e.target.result;
            } else {
                if (placeholder) {
                    placeholder.remove();
                }
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'プロフィール画像';
                img.id = 'profile-image';
                preview.appendChild(img);
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
