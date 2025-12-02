@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="container">
    <h1 class="page-title">商品の出品</h1>

    <section class="section">
        <h2 class="section-title">商品画像</h2>
        <div class="image-upload-area">
            <button class="upload-button">画像を選択する</button>
        </div>
    </section>

    <section class="section">
        <h2 class="section-title">商品の詳細</h2>

        <div class="form-group">
            <label class="label">カテゴリー</label>
            <div class="category-tags">
                <span class="tag">テクノロジー</span>
                <span class="tag">生活</span>
                <span class="tag tag-active">ビジネス</span>
                <span class="tag">マーケティング</span>
                <span class="tag">デザイン</span>
                <span class="tag">エンジニア</span>
                <span class="tag">IT</span>
                <span class="tag">ライフ</span>
                <span class="tag">キャリア</span>
                <span class="tag">マネジメント</span>
                <span class="tag">コンサルティング</span>
                <span class="tag">プロダクト開発</span>
                <span class="tag">ビジネススキル</span>
                <span class="tag">コーポレートコーチ</span>
            </div>
        </div>

        <div class="form-group">
            <label class="label">商品の状態</label>
            <select class="select-box">
                <option>選択してください</option>
            </select>
        </div>
    </section>

    <section class="section">
        <h2 class="section-title">商品名と説明</h2>

        <div class="form-group">
            <label class="label">商品名</label>
            <input type="text" class="input-field" />
        </div>

        <div class="form-group">
            <label class="label">ブランド名</label>
            <input type="text" class="input-field" />
        </div>

        <div class="form-group">
            <label class="label">商品の説明</label>
            <textarea class="textarea-field"></textarea>
        </div>

        <div class="form-group">
            <label class="label">販売価格</label>
            <input type="text" class="input-field input-price" value="¥" />
        </div>
    </section>

    <button class="submit-button">出品する</button>
</div>
@endsection