<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>心桜 認知症チェック</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>

<body>
    <div class="container">
        <header>
            <img src="{{ asset('storage/画像1.png') }}" alt="ロゴ" class="logo">
            <div class="social-icons">
                <a href="https://twitter.com/intent/tweet?text=心桜で簡易認知症検査を行い、あなたの大切な方の早期異常に気づいて適切な医療に繋げましょう。&url={{ url('/') }}" class="text-dark mx-2" target="_blank"><i class="fab fa-twitter" style="color: #1DA1F2;"></i></a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url('/') }}&quote=心桜で簡易認知症検査を行い、あなたの大切な方の早期異常に気づいて適切な医療に繋げましょう。" class="text-dark mx-2" target="_blank"><i class="fab fa-facebook" style="color: #4267B2;"></i></a>
                <a href="https://social-plugins.line.me/lineit/share?url={{ url('/') }}&text=心桜で簡易認知症検査を行い、あなたの大切な方の早期異常に気づいて適切な医療に繋げましょう。" class="text-dark mx-2" target="_blank"><i class="fab fa-line" style="color: #00B900;"></i></a>
                <a href="https://www.instagram.com/sharer.php?u={{ url('/') }}&text=心桜で簡易認知症検査を行い、あなたの大切な方の早期異常に気づいて適切な医療に繋げましょう。" class="text-dark mx-2" target="_blank"><i class="fab fa-instagram" style="color: #E1306C;"></i></a>
            </div>
        </header>
        <main class="text-center mt-5">
            <div class="title-wrapper">
                <span class="title-main">心桜</span>
                <span class="title-sub">認知症認知症チェック</span>
            </div>
            <p class="delayed-text mt-4">大切な人の異常や認知症の早期発見により<br>いち早く適切な医療や介護に繋げ、住み慣れた地域で支える。</p>
            <img src="{{ asset('storage/63923.png') }}" alt="イラスト" class="img-fluid img-custom mt-3">
            <br>
            <a href="{{ url('/quiz') }}" class="btn btn-custom btn-lg mt-3">簡易チェックを実施する</a>
        </main>
        <div class="falling-petals"></div>
        <canvas id="sakura-canvas"></canvas>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ asset('js/site.js') }}"></script>
</body>

</html>