@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="container">
    <h1 class="page-title">商品の出品</h1>

    <form action="/sell" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        <section class="section">
            <h2 class="section-title">商品画像</h2>
            <div class="image-upload-area">
                <input type="file" id="image" name="image" accept="image/*">
                <div id="image-preview" style="display: none; margin-top: 20px;">
                    <img id="preview-img" src="" alt="プレビュー" style="max-width: 100%; max-height: 300px; border-radius: 4px;">
                </div>
            </div>
            @error('image')
            <span class="error-message">{{ $message }}</span>
            @enderror
        </section>

        <section class="section">
            <h2 class="section-title">商品の詳細</h2>

            <div class="form-group">
                <label class="label">カテゴリー</label>
                <div class="category-tags" role="group" aria-labelledby="category-label">
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="ファッション" class="category-checkbox" {{ (is_array(old('categories')) && in_array('ファッション', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">ファッション</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="家電" class="category-checkbox" {{ (is_array(old('categories')) && in_array('家電', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">家電</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="インテリア" class="category-checkbox" {{ (is_array(old('categories')) && in_array('インテリア', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">インテリア</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="レディース" class="category-checkbox" {{ (is_array(old('categories')) && in_array('レディース', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">レディース</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="メンズ" class="category-checkbox" {{ (is_array(old('categories')) && in_array('メンズ', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">メンズ</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="コスメ" class="category-checkbox" {{ (is_array(old('categories')) && in_array('コスメ', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">コスメ</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="本" class="category-checkbox" {{ (is_array(old('categories')) && in_array('本', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">本</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="ゲーム" class="category-checkbox" {{ (is_array(old('categories')) && in_array('ゲーム', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">ゲーム</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="スポーツ" class="category-checkbox" {{ (is_array(old('categories')) && in_array('スポーツ', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">スポーツ</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="キッチン" class="category-checkbox" {{ (is_array(old('categories')) && in_array('キッチン', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">キッチン</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="ハンドメイド" class="category-checkbox" {{ (is_array(old('categories')) && in_array('ハンドメイド', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">ハンドメイド</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="アクセサリー" class="category-checkbox" {{ (is_array(old('categories')) && in_array('アクセサリー', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">アクセサリー</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="おもちゃ" class="category-checkbox" {{ (is_array(old('categories')) && in_array('おもちゃ', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">おもちゃ</span>
                    </label>
                    <label class="tag-checkbox">
                        <input type="checkbox" name="categories[]" value="ベビー・キッズ" class="category-checkbox" {{ (is_array(old('categories')) && in_array('ベビー・キッズ', old('categories'))) ? 'checked' : '' }}>
                        <span class="tag">ベビー・キッズ</span>
                    </label>
                </div>
                @error('categories')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="label" for="condition_id">商品の状態</label>
                <select id="condition_id" class="select-box" name="condition_id">
                    <option value="">選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>{{ $condition->name }}</option>
                    @endforeach
                </select>
                @error('condition_id')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </section>

        <section class="section">
            <h2 class="section-title">商品名と説明</h2>

            <div class="form-group">
                <label class="label" for="name">商品名</label>
                <input type="text" id="name" class="input-field" name="name" value="{{ old('name') }}" />
                @error('name')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="label" for="brand">ブランド名</label>
                <input type="text" id="brand" class="input-field" name="brand" value="{{ old('brand') }}" />
                @error('brand')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="label" for="info">商品の説明</label>
                <textarea id="info" class="textarea-field" name="info">{{ old('info') }}</textarea>
                @error('info')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="label" for="price">販売価格</label>
                <input type="text" id="price" class="input-field input-price" name="price" value="{{ old('price') }}" />
                @error('price')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </section>

        <button type="submit" class="submit-button">出品する</button>
    </form>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});
</script>
@endsection