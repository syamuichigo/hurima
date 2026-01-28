@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>住所の変更</h1>
    <form method="POST" action="/address/update">
        @csrf
        {{-- 購入画面から遷移してきた場合、どの商品かを保持する --}}
        @if(request('content_id'))
        <input type="hidden" name="content_id" value="{{ request('content_id') }}">
        @endif
        @foreach ($profile as $profile)
        <div class="form-group">
            <label for="postcode">郵便番号</label>
            <input type="text" id="postcode" class="form-input" name="postcode" value="{{ old('postcode', $profile->postcode) }}" placeholder="例: 123-4567">
            @error('postcode')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" class="form-input" name="address" value="{{ old('address', $profile->address) }}" placeholder="例: 東京都渋谷区1-2-3">
            @error('address')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" id="building" class="form-input" name="building" value="{{ old('building', $profile->building) }}" placeholder="例: マンション101号室">
            @error('building')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        @endforeach
        <button type="submit" class="submit-btn">更新する</button>
    </form>
</div>
@endsection