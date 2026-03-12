<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .rating { color: #ffd700; font-size: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <p>{{ $ratedUser->display_name ?? $ratedUser->username }} 様</p>
        <p>{{ $raterUser->display_name ?? $raterUser->username }} さんから取引の評価を受けました。</p>
        <p>評価：<span class="rating">{{ str_repeat('★', $rating) }}{{ str_repeat('☆', 5 - $rating) }}</span> （{{ $rating }}/5）</p>
        <p><a href="{{ url('/mypage') }}">マイページ</a>でご確認ください。</p>
    </div>
</body>
</html>
