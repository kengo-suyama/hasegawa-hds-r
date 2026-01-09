<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#f2c6d8">
    <title>心桜 認知症チェック</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>

<body>
    <div id="networkStatus" class="network-status" role="alert" aria-live="polite">
        オフラインです。通信環境をご確認ください。
    </div>
    <div id="globalLoading" class="loading-overlay" aria-live="polite" aria-busy="true">
        <div class="loading-spinner" aria-hidden="true"></div>
        <div class="loading-text">読み込み中...</div>
    </div>
    <div class="theme-toggle">
        <button type="button" class="btn btn-outline-primary" id="themeToggleBtn">ダークモード</button>
    </div>
    <div id="petalLayer" aria-hidden="true"></div>
    <div class="container">
        <header>
            <img src="{{ asset('storage/画像1.png') }}" alt="ロゴ" class="logo">
        </header>
        <main class="text-center mt-5">
            <div class="title-wrapper">
                <span class="title-main">心桜</span>
                <span class="title-sub">認知症チェック</span>
            </div>
            <p class="delayed-text mt-4">大切な人の異常や認知症の早期発見により<br>いち早く適切な医療や介護に繋げ、住み慣れた地域で支える。</p>
            <img src="{{ asset('storage/63923.png') }}" alt="イラスト" class="img-fluid img-custom mt-3">
            <br>
            <a href="{{ url('/quiz') }}" class="btn btn-custom btn-lg mt-3">簡易チェックを実施する</a>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ asset('js/site.js') }}"></script>
</body>

</html>




