@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>住所の変更</h1>

    <form>
        <div class="form-group">
            <label>郵便番号</label>
            <input type="text" class="form-input">
        </div>

        <div class="form-group">
            <label>住所</label>
            <input type="text" class="form-input">
        </div>

        <div class="form-group">
            <label>建物名</label>
            <input type="text" class="form-input">
        </div>

        <button type="submit" class="submit-btn">更新する</button>
    </form>
</div>
@endsection