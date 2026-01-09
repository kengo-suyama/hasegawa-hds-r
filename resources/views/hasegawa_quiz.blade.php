<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>心桜 認知症チェック(改良版)</title>
  <!-- Bootstrap & Font Awesome -->
  <link
    rel="stylesheet"
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@400;500;700&display=swap');

    :root {
      --ink: #2d2a2b;
      --ink-muted: #6a5f64;
      --bg: #f3d6e2;
      --bg-soft: #f7e2ea;
      --panel: #fff7fa;
      --panel-accent: #f5d7e1;
      --border: #e8b6c7;
      --primary: #b24f6e;
      --primary-strong: #8f3b54;
      --accent: #c86b86;
      --accent-soft: #f2cbd8;
      --danger: #b04a4a;
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: 'Zen Kaku Gothic New', 'Hiragino Kaku Gothic ProN', 'Meiryo', sans-serif;
      background-color: var(--bg);
      color: var(--ink);
      font-size: 1.25rem;
      line-height: 1.7;
      overflow-x: hidden;
      overflow-y: auto;
      position: relative;
    }

    :root.theme-dark {
      --ink: #f4e9ee;
      --ink-muted: #d0b7c3;
      --bg: #2b1c22;
      --bg-soft: #3b2630;
      --panel: #3a252d;
      --panel-accent: #4a2f3a;
      --border: #5c3a47;
      --primary: #d07a92;
      --primary-strong: #b5647f;
      --accent: #d98aa1;
      --accent-soft: #4a2f3a;
      --danger: #d06b6b;
    }

    :root.theme-light {
      --ink: #2d2a2b;
      --ink-muted: #6a5f64;
      --bg: #f3d6e2;
      --bg-soft: #f7e2ea;
      --panel: #fff7fa;
      --panel-accent: #f5d7e1;
      --border: #e8b6c7;
      --primary: #b24f6e;
      --primary-strong: #8f3b54;
      --accent: #c86b86;
      --accent-soft: #f2cbd8;
      --danger: #b04a4a;
    }

    .title-section h2 {
      font-weight: 700;
      color: var(--primary-strong);
      text-align: center;
      margin-top: 2rem;
      margin-bottom: 1.5rem;
      font-size: 2.6rem;
      letter-spacing: 0.05em;
    }

    .theme-toggle {
      position: fixed;
      top: 12px;
      right: 12px;
      z-index: 2100;
    }

    .theme-toggle .btn {
      font-size: 0.95rem;
      padding: 0.45rem 0.75rem;
      border-radius: 10px;
    }

    .instructions {
      margin: 0 auto;
      max-width: 720px;
      background: var(--panel);
      border: 1px solid var(--border);
      border-radius: 18px;
      padding: 1.5rem 1.75rem;
      box-shadow: 0 12px 28px rgba(20, 30, 40, 0.12);
    }

    .instructions h5 {
      font-size: 1.35rem;
      font-weight: 700;
      margin-top: 1rem;
    }

    .btn-start {
      display: block;
      margin: 1.2rem auto 0;
      font-size: 1.4rem;
      font-weight: 700;
      padding: 0.8rem 1.6rem;
      border-radius: 14px;
    }

    .form-inline {
      flex-wrap: wrap;
      gap: 0.5rem 0.75rem;
      align-items: center;
    }

    .form-control {
      font-size: 1.1rem;
      padding: 0.6rem 0.9rem;
      border-radius: 10px;
      border: 1px solid var(--border);
      line-height: 1.4;
      height: 52px;
    }

    select.form-control {
      padding-top: 0.3rem;
      padding-bottom: 0.3rem;
      line-height: 1.2;
      vertical-align: middle;
    }

    .modal-dialog {
      max-width: 760px;
    }

    .modal-dialog.modal-lg {
      max-width: 920px;
    }

    .modal {
      padding: 0 !important;
    }

    .modal-dialog,
    .modal-dialog.modal-lg {
      max-width: 100%;
      width: 100%;
      height: 100%;
      margin: 0;
    }

    .modal-dialog-centered {
      min-height: 100%;
      display: block;
    }

    .modal-content {
      height: 100%;
      border-radius: 0;
      border: 1px solid var(--border);
      box-shadow: 0 14px 30px rgba(20, 30, 40, 0.18);
      display: flex;
      flex-direction: column;
    }

    .modal-backdrop {
      background-color: var(--bg);
    }

    .modal-backdrop.show {
      opacity: 0.7;
    }

    .modal-header {
      background-color: rgba(245, 215, 225, 0.92);
      border-bottom: none;
    }

    .modal-title {
      font-size: 1.4rem;
      font-weight: 700;
    }

    .modal-body {
      background-color: rgba(255, 247, 250, 0.94);
      padding: 1.5rem;
      overflow-y: auto;
      flex: 1 1 auto;
    }

    .modal-footer {
      background-color: rgba(245, 215, 225, 0.92);
      border-top: none;
      flex: 0 0 auto;
    }

    .question {
      font-weight: 700;
      font-size: 1.55rem;
      margin-bottom: 1.2rem;
      background: var(--bg-soft);
      border-left: 8px solid var(--accent);
      padding: 0.9rem 1.1rem;
      border-radius: 12px;
    }

    .question p {
      margin-bottom: 0;
    }

    .advice {
      font-size: 1.05rem;
      margin-top: 1rem;
    }

    .btn-next {
      margin-top: 1rem;
    }

    .btn {
      font-size: 1.15rem;
      padding: 0.7rem 1rem;
      border-radius: 12px;
    }

    .btn-primary {
      background-color: var(--primary);
      border-color: var(--primary);
    }

    .btn-primary:hover,
    .btn-primary:focus {
      background-color: var(--primary-strong);
      border-color: var(--primary-strong);
    }

    .btn-outline-primary {
      color: var(--primary);
      border-color: var(--primary);
    }

    .btn-outline-primary:hover,
    .btn-outline-primary:focus {
      background-color: var(--primary);
      color: #fff;
    }

    .btn-outline-danger {
      color: var(--danger);
      border-color: var(--danger);
    }

    .btn-outline-danger:hover,
    .btn-outline-danger:focus {
      background-color: var(--danger);
      color: #fff;
    }

    /* ワンポイントアドバイス用 */
    .extra-info {
      background-color: var(--panel-accent);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 12px 14px;
      margin-top: 15px;
    }

    .extra-info h6 {
      font-weight: 700;
      margin-bottom: 5px;
    }

    .extra-info p {
      margin-bottom: 0.5rem;
    }

    .q3-hint {
      background: var(--accent-soft);
      border-radius: 12px;
      padding: 0.7rem 1rem;
      font-weight: 700;
      color: var(--primary-strong);
      font-size: 1.4rem;
      margin-bottom: 1rem;
    }

    .q3-countdown {
      background: var(--accent-soft);
      border-radius: 12px;
      padding: 0.7rem 1rem;
      font-weight: 700;
      color: var(--primary-strong);
      font-size: 1.4rem;
      margin-bottom: 1rem;
      text-align: center;
    }

    .word-set-panel {
      background: var(--panel);
      border: 2px solid var(--border);
      border-radius: 12px;
      padding: 0.9rem 1rem;
      text-align: center;
    }

    .word-set-label {
      font-size: 1.05rem;
      color: var(--ink-muted);
    }

    .word-set-words {
      font-size: 1.55rem;
      font-weight: 700;
      letter-spacing: 0.05em;
      margin-top: 0.4rem;
    }

    .word-set-options {
      display: grid;
      gap: 0.5rem;
      margin-top: 1rem;
    }

    .word-set-option {
      display: flex;
      align-items: center;
      gap: 0.6rem;
      font-size: 1.15rem;
      padding: 0.4rem 0.6rem;
      border-radius: 10px;
      background: #fff;
      border: 1px solid var(--border);
    }

    .word-set-option input {
      transform: scale(1.2);
    }

    .recall-panel {
      background: var(--panel-accent);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 0.9rem 1rem;
      margin-bottom: 1rem;
    }

    .recall-title {
      font-size: 1.05rem;
      color: var(--ink-muted);
      margin-bottom: 0.4rem;
    }

    .recall-words {
      font-size: 1.45rem;
      font-weight: 700;
      letter-spacing: 0.04em;
    }

    .recall-hint {
      margin-top: 0.4rem;
      font-size: 1.05rem;
      color: var(--ink-muted);
    }

    .q7-score-grid {
      display: grid;
      gap: 0.75rem;
      margin-top: 1rem;
    }

    .q7-score-row {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      align-items: center;
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 0.75rem 1rem;
    }

    .q7-score-word {
      flex: 1 1 220px;
    }

    .q7-word-label {
      font-size: 1.2rem;
      font-weight: 700;
    }

    .q7-word-hint {
      font-size: 1rem;
      color: var(--ink-muted);
    }

    .q7-score-select {
      min-width: 220px;
    }

    .q8-current {
      border: 2px solid var(--border);
      border-radius: 16px;
      padding: 1rem;
      text-align: center;
      background: var(--bg-soft);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.6rem;
    }

    .q8-current-name {
      font-size: 1.6rem;
      font-weight: 700;
      margin-bottom: 0.6rem;
    }

    .q8-current-image {
      max-width: 100%;
      max-height: 360px;
      border-radius: 12px;
      border: 3px solid #fff;
      background: #fff;
      box-shadow: 0 10px 18px rgba(20, 30, 40, 0.16);
      display: block;
      margin: 0 auto;
    }

    .q8-progress {
      margin-top: 0.5rem;
      font-size: 1.05rem;
      color: var(--ink-muted);
    }

    .q8-image-actions {
      width: min(360px, 100%);
      display: grid;
      gap: 0.5rem;
      margin-top: 0.4rem;
    }

    .q8-controls {
      display: grid;
      gap: 0.5rem;
      margin-top: 1rem;
    }

    .q8-retry-note {
      font-size: 0.95rem;
      color: var(--ink-muted);
      text-align: center;
    }

    .q8-review {
      margin-top: 1rem;
      background: #fff;
      border: 1px dashed var(--border);
      border-radius: 12px;
      padding: 0.8rem 0.9rem;
    }

    .q8-review-title {
      font-size: 1.05rem;
      color: var(--ink-muted);
    }

    .q8-shown-list {
      display: grid;
      gap: 0.75rem;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      margin-top: 0.6rem;
    }

    .q8-shown-card {
      background: #fff;
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 0.5rem;
      text-align: center;
    }

    .q8-shown-card img {
      width: 100%;
      height: 90px;
      object-fit: contain;
    }

    .q8-shown-name {
      margin-top: 0.4rem;
      font-size: 1rem;
      font-weight: 600;
    }

    .q8-placeholder {
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f7f7f7;
      border: 2px dashed #d4c9b7;
      color: #6b6b6b;
      border-radius: 8px;
      min-height: 90px;
      padding: 0.5rem;
      font-size: 1.05rem;
    }

    .helper-note {
      font-size: 0.95rem;
      color: var(--ink-muted);
    }

    .q8-retry-help {
      text-align: center;
      margin-top: 0.3rem;
    }

    .age-note {
      margin-top: 0.2rem;
    }

    .vegetable-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 0.6rem;
      margin-top: 0.6rem;
    }

    .vegetable-input {
      font-size: 1.05rem;
    }

    .network-status {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      padding: 0.6rem 1rem;
      background: #b24f6e;
      color: #fff;
      text-align: center;
      font-weight: 700;
      z-index: 2200;
      display: none;
    }

    .back-notice {
      position: fixed;
      bottom: 16px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(178, 79, 110, 0.95);
      color: #fff;
      padding: 0.9rem 1.2rem;
      border-radius: 14px;
      box-shadow: 0 8px 18px rgba(20, 30, 40, 0.2);
      display: none;
      z-index: 2200;
      text-align: center;
      max-width: min(90vw, 520px);
    }

    .back-notice .btn {
      margin-top: 0.6rem;
    }

    .loading-overlay {
      position: fixed;
      inset: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      background: rgba(248, 221, 231, 0.88);
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.2s ease;
      z-index: 2300;
    }

    .loading-overlay.is-visible {
      opacity: 1;
      visibility: visible;
    }

    .loading-spinner {
      width: 48px;
      height: 48px;
      border: 4px solid rgba(178, 79, 110, 0.35);
      border-top-color: var(--primary-strong);
      border-radius: 50%;
      animation: spin 0.9s linear infinite;
    }

    .loading-text {
      font-weight: 700;
      color: var(--ink);
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    #petalLayer {
      position: fixed;
      inset: 0;
      pointer-events: none;
      overflow: hidden;
      z-index: 0;
    }

    .title-section,
    #introSection {
      position: relative;
      z-index: 1;
    }

    .petal {
      position: absolute;
      top: -12vh;
      animation: petal-fall var(--fall-duration) linear infinite;
      will-change: transform;
    }

    .petal-sway {
      display: block;
      width: var(--petal-size);
      height: calc(var(--petal-size) * 1.2);
      background: radial-gradient(circle at 30% 30%, var(--petal-highlight) 0%, var(--petal-color) 55%, var(--petal-shadow) 100%);
      clip-path: polygon(50% 18%, 63% 5%, 80% 6%, 94% 22%, 94% 40%, 80% 60%, 50% 100%, 20% 60%, 6% 40%, 6% 22%, 20% 6%, 37% 5%);
      opacity: var(--petal-opacity);
      animation: petal-sway var(--sway-duration) ease-in-out infinite;
      will-change: transform;
    }

    @keyframes petal-fall {
      to {
        transform: translate3d(var(--fall-x), 110vh, 0);
      }
    }

    @keyframes petal-sway {
      0% {
        transform: translateX(0) rotate(0deg);
      }
      50% {
        transform: translateX(var(--sway-x)) rotate(var(--sway-rotate));
      }
      100% {
        transform: translateX(0) rotate(var(--sway-rotate-end));
      }
    }

    #q8FullscreenTarget:fullscreen {
      background: #fff7fa;
      padding: 2rem;
    }

    #q8FullscreenTarget:fullscreen .q8-current-name {
      font-size: 2.2rem;
    }

    #q8FullscreenTarget:fullscreen .q8-current-image {
      max-height: 80vh;
    }

    #q8FullscreenTarget:-webkit-full-screen {
      background: #fff7fa;
      padding: 2rem;
    }

    #q8FullscreenTarget:-webkit-full-screen .q8-current-name {
      font-size: 2.2rem;
    }

    #q8FullscreenTarget:-webkit-full-screen .q8-current-image {
      max-height: 80vh;
    }

    body.q8-fallback-active {
      overflow: hidden;
    }

    #q8FullscreenTarget.q8-fullscreen-fallback {
      position: fixed;
      inset: 0;
      z-index: 2000;
      background: #fff7fa;
      padding: 2rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    #q8FullscreenTarget.q8-fullscreen-fallback .q8-current-name {
      font-size: 2.2rem;
    }

    #q8FullscreenTarget.q8-fullscreen-fallback .q8-current-image {
      max-height: 80vh;
    }

    .theme-dark .instructions {
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.35);
    }

    .theme-dark .modal-content {
      border-color: var(--border);
    }

    .theme-dark .modal-body {
      background-color: rgba(58, 37, 45, 0.96);
    }

    .theme-dark .modal-header,
    .theme-dark .modal-footer {
      background-color: rgba(63, 42, 51, 0.92);
    }

    .theme-dark .extra-info,
    .theme-dark .recall-panel,
    .theme-dark .q8-current,
    .theme-dark .word-set-panel {
      background: #3b2831;
    }

    .theme-dark .q8-current-image {
      border-color: #3a252d;
      background: #2f2027;
    }

    .theme-dark .q8-placeholder {
      background: #2f2a2c;
      color: #e5d7dd;
      border-color: #5c3a47;
    }

    .theme-dark .loading-overlay {
      background: rgba(30, 20, 25, 0.9);
    }

    .theme-dark .back-notice {
      background: rgba(61, 42, 50, 0.95);
      color: #f7edf1;
    }

    .theme-dark .q3-hint,
    .theme-dark .q3-countdown {
      background: #4a2f3a;
      color: var(--ink);
    }

    .theme-dark .word-set-option,
    .theme-dark .q7-score-row,
    .theme-dark .q8-review,
    .theme-dark .q8-shown-card {
      background: #3a252d;
      border-color: var(--border);
      color: var(--ink);
    }

    .theme-dark .word-set-label,
    .theme-dark .recall-title,
    .theme-dark .recall-hint,
    .theme-dark .q7-word-hint,
    .theme-dark .q8-review-title,
    .theme-dark .q8-progress,
    .theme-dark .q8-retry-note,
    .theme-dark .helper-note {
      color: var(--ink-muted);
    }

    .theme-dark .recall-words,
    .theme-dark .q7-word-label,
    .theme-dark .q8-shown-name {
      color: var(--ink);
    }

    @media (max-width: 768px) {
      body {
        font-size: 1.35rem;
      }

      .title-section h2 {
        font-size: 2rem;
      }

      .instructions {
        padding: 1.2rem;
      }

      .btn-start {
        font-size: 1.2rem;
      }

      .question {
        font-size: 1.5rem;
        margin-bottom: 0.9rem;
      }

      .q8-current-image {
        max-height: 260px;
      }

      .modal-header,
      .modal-footer {
        padding: 0.8rem 1rem;
      }

      .modal-body {
        padding: 1.1rem;
      }
    }
  </style>
</head>

<body>
  <div id="networkStatus" class="network-status" role="alert" aria-live="polite">
    オフラインです。通信環境をご確認ください。
  </div>
  <div id="backNotice" class="back-notice" role="status" aria-live="polite">
    ブラウザの戻る操作で設問が閉じました。続きに戻る場合は下のボタンを押してください。
    <div>
      <button type="button" class="btn btn-light btn-sm" data-action="resume-modal">設問に戻る</button>
    </div>
  </div>
  <div id="globalLoading" class="loading-overlay" aria-live="polite" aria-busy="true">
    <div class="loading-spinner" aria-hidden="true"></div>
    <div class="loading-text">読み込み中...</div>
  </div>
  <div class="theme-toggle">
    <button type="button" class="btn btn-outline-primary" id="themeToggleBtn">ダークモード</button>
  </div>
  <div id="petalLayer" aria-hidden="true"></div>
  <div class="title-section">
    <h2>心桜 認知症チェック</h2>
  </div>

  <!-- ================================
       イントロ画面（生年月日入力）
       ================================ -->
  <div class="container instructions" id="introSection">
    <h5>検査の導入にあたっての注意</h5>
    <p>
      検査を開始する際は、「最近、物忘れでお困りではないですか？」などと
      やさしく尋ねることをお勧めします。<br />
      患者様がリラックスして検査に臨めるよう、心を配ってください。
    </p>
    <h5>検査を終了した後の注意点</h5>
    <p>
      検査が終わったら、「お疲れ様でした。どうですか、疲れましたか？」と
      声をかけ、患者様が安心できるように努めてください。
    </p>

    <!-- 生年月日入力フォーム (プルダウン: 西暦年 + 月 + 日) -->
    <div class="form-inline mb-3">
      <label for="birthYear" class="mr-2">西暦:</label>
      <select class="form-control mr-2" id="birthYear" name="birthYear" data-old="{{ old('birthYear') }}" style="width: 140px;"></select>

      <label for="birthMonth" class="mr-2">月:</label>
      <select class="form-control mr-2" id="birthMonth" name="birthMonth" style="width: 90px;">
        @for ($i = 1; $i <= 12; $i++)
          <option value="{{ $i }}" {{ old('birthMonth') == $i ? 'selected' : '' }}>{{ $i }}月</option>
          @endfor
      </select>

      <label for="birthDay" class="mr-2">日:</label>
      <select class="form-control" id="birthDay" name="birthDay" style="width: 90px;">
        @for ($d = 1; $d <= 31; $d++)
          <option value="{{ $d }}" {{ old('birthDay') == $d ? 'selected' : '' }}>{{ $d }}日</option>
          @endfor
      </select>
    </div>
    <div class="helper-note mt-2">※本検査は原則18歳（成人）以上を対象としています。18歳未満の場合は実施可否をご判断ください。</div>
    <div class="d-flex mb-2" style="gap:8px;">
      <button class="btn btn-primary" data-action="calculate-age">年齢を計算</button>
      <button class="btn btn-warning" id="birthUnknownBtn" data-action="mark-birth-unknown">生年月日が不明瞭な場合はこちら</button>
    </div>

    <!-- 生年月日不明時のフラッシュメッセージ -->
    <div id="birthUnknownFlash" style="display:none;" class="alert alert-warning" role="alert"></div>

    <div class="mt-3" id="calculatedAgeArea" style="display: none;">
      <p>推定年齢: <span id="calculatedAgeSpan" style="font-weight:bold;"></span> 歳</p>
      <p class="helper-note age-note">※保険証などで確認した年齢と一致するかご確認ください</p>
    </div>

    <button
      class="btn btn-success btn-start"
      data-action="start-quiz"
      disabled
      id="startQuizBtn">
      検査を開始する
    </button>
  </div>

  <!-- ========== Modal 1 (設問1: 2択) ========== -->
  <div class="modal fade" data-backdrop="static" data-keyboard="false" id="modalQuestion1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問1: 年齢を答えてください。</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- 計算した年齢をここに表示 -->
          <div class="question" id="q1Question">
            <p>
              先ほど計算した年齢は
              <span id="calculatedAgeDisplay" style="font-weight:bold;"></span>
              歳ですね。<br />
              受検者が答えた年齢がこの年齢 ±2 以内なら「正解」ボタン、<br />
              それ以外なら「不正解」ボタンを押してください。
            </p>
          </div>
          <div class="helper-note mb-3">※設問1の年齢確認は原則18歳（成人）以上を対象にしてください。</div>
          <!-- 2ボタンのみ -->
          <button
            class="btn btn-outline-primary btn-block mb-2"
            data-action="answer" data-score="1" data-next="2">
            正解（±2年以内）
          </button>
          <button
            class="btn btn-outline-danger btn-block mb-2"
            data-action="answer" data-score="0" data-next="2">
            不正解（±2年外）
          </button>
          <div class="advice alert alert-secondary mt-3">
            検査者は「正しい年齢」を把握しておき、±2年かどうかを判断してください。
          </div>

          <!-- ワンポイントアドバイス（設問1） -->
          <div class="extra-info mt-3">
            <h6>【設問1のポイント：年齢】</h6>
            <p>
              <strong>なぜ ±2年を正解とする？</strong><br />
              数え年で答える方、誕生日を迎えているかどうかなど、年齢に誤差が生じる可能性があるためです。
            </p>
            <p>
              <strong>生年月日を言えたのに年齢が答えられない場合は？</strong><br />
              その場合は0点になります。年齢という数値を理解しているかどうかが重要です。
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 2 (設問2) ========== -->
  <div
    class="modal fade" data-backdrop="static" data-keyboard="false"
    id="modalQuestion2"
    tabindex="-1"
    aria-labelledby="modalQuestion2Label"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalQuestion2Label">設問2</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Q1スキップ通知領域（必要時にJSで内容を挿入） -->
          <div id="q1SkipNotice"></div>
          <!-- 設問2の正答日表示（検査者用） -->
          <div id="q2CorrectDate" style="margin-bottom:8px;"></div>
          <div class="question" id="q2Question">
            今日は何年の何月何日ですか？ 何曜日ですか？
          </div>
          <!-- 年・月・日・曜日の正誤選択 -->
          <div class="form-row">
            <div class="form-group col-md-3">
              <label for="yearSelect">年</label>
              <select class="form-control" id="yearSelect" name="yearSelect" data-old="{{ old('yearSelect') }}">
                <option value="0" {{ old('yearSelect', '0') == '0' ? 'selected' : '' }}>不正解</option>
                <option value="1" {{ old('yearSelect') == '1' ? 'selected' : '' }}>正解</option>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="monthSelect">月</label>
              <select class="form-control" id="monthSelect" name="monthSelect" data-old="{{ old('monthSelect') }}">
                <option value="0" {{ old('monthSelect', '0') == '0' ? 'selected' : '' }}>不正解</option>
                <option value="1" {{ old('monthSelect') == '1' ? 'selected' : '' }}>正解</option>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="daySelect">日</label>
              <select class="form-control" id="daySelect" name="daySelect" data-old="{{ old('daySelect') }}">
                <option value="0" {{ old('daySelect', '0') == '0' ? 'selected' : '' }}>不正解</option>
                <option value="1" {{ old('daySelect') == '1' ? 'selected' : '' }}>正解</option>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="weekdaySelect">曜日</label>
              <select class="form-control" id="weekdaySelect" name="weekdaySelect" data-old="{{ old('weekdaySelect') }}">
                <option value="0" {{ old('weekdaySelect', '0') == '0' ? 'selected' : '' }}>不正解</option>
                <option value="1" {{ old('weekdaySelect') == '1' ? 'selected' : '' }}>正解</option>
              </select>
            </div>
          </div>

          <div class="advice alert alert-secondary mt-3">
            時間の見当識を問う設問です。逆順で聞いてもOKです。
          </div>

          <!-- ワンポイントアドバイス (設問2) -->
          <div class="extra-info">
            <h6>【設問2のポイント：今日の日付・曜日】</h6>
            <p>
              <strong>順番は固定？</strong><br />
              特に固定ではありません。「何月何日？」「今日は何曜日？」と逆から聞く方が答えやすい場合もあります。
            </p>
            <p>
              <strong>何が大切？</strong><br />
              日付を正しく理解できているかを確認することが目的です。
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-primary btn-next"
            data-action="submit-q2">
            次へ
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 3 ========== -->
  <div
    class="modal fade" data-backdrop="static" data-keyboard="false"
    id="modalQuestion3"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問3</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question" id="q3Question">
            私たちが今いるところはどこですか？
          </div>
          <div id="q3HintCountdown" class="q3-countdown" style="display: none;"></div>
          <div id="q3Hint" class="q3-hint" style="display: none;">
            ヒント: 家ですか？病院ですか？施設ですか？
          </div>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            data-action="answer" data-score="2" data-next="4">
            自発的に正解 (2点)
          </button>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            data-action="answer" data-score="1" data-next="4">
            ヒント後に正解 (1点)
          </button>
          <button
            class="btn btn-outline-danger btn-block mb-2"
            data-action="answer" data-score="0" data-next="4">
            不正解 (0点)
          </button>
          <div class="advice alert alert-secondary mt-3">
            病院名を言えなくても、「病院・施設にいる」と理解していれば正解です。5秒後にヒントを出します。
          </div>

          <!-- ワンポイントアドバイス（設問3） -->
          <div class="extra-info">
            <h6>【設問3のポイント：場所の見当識】</h6>
            <p>
              <strong>病院名を答えられなくてもいい？</strong><br />
              はい。「病院」「施設」「家」など本質的にどこかを理解していればOKです。
            </p>
            <p>
              <strong>ヒントはどうする？</strong><br />
              「家ですか？」「施設ですか？」など、複数の選択肢を挙げて導いてもかまいません。
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

    <!-- ========== Modal 4 ========== -->
  <div
    class="modal fade" data-backdrop="static" data-keyboard="false"
    id="modalQuestion4"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問4</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question" id="q4Question">
            これから言う3つの言葉を覚えてください。後で思い出してもらいます。
          </div>
          <div class="word-set-panel">
            <div class="word-set-label">今から使う言葉（どちらか1つを選択）</div>
            <div id="q4WordsDisplay" class="word-set-words"></div>
          </div>
          <div class="word-set-options">
            <label class="word-set-option">
              <input type="radio" name="q4WordSet" value="setA" checked>
              セットA：桜・猫・電車
            </label>
            <label class="word-set-option">
              <input type="radio" name="q4WordSet" value="setB">
              セットB：梅・犬・自動車
            </label>
          </div>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            data-action="answer" data-score="3" data-next="5">
            全て言えた (3点)
          </button>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            data-action="answer" data-score="2" data-next="5">
            2つ言えた (2点)
          </button>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            data-action="answer" data-score="1" data-next="5">
            1つ言えた (1点)
          </button>
          <button
            class="btn btn-outline-danger btn-block mb-2"
            data-action="answer" data-score="0" data-next="5">
            言えない (0点)
          </button>
          <div class="advice alert alert-secondary mt-3">
            言えたあとで何度か繰り返し、3つ全て覚えてもらいましょう（最大3回）。
          </div>

          <!-- ワンポイントアドバイス（設問4） -->
          <div class="extra-info">
            <h6>【設問4のポイント：3つの言葉の記銘】</h6>
            <p>
              <strong>なぜ植物・動物・乗り物？</strong><br />
              連想しやすいが、互いに関係がない3種類を選んでいます。
            </p>
            <p>
              <strong>2つしか覚えられない場合？</strong><br />
              2点で採点しますが、覚え直してもらうよう最大3回まで繰り返してOKです。
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 5 ========== -->
  <div
    class="modal fade" data-backdrop="static" data-keyboard="false"
    id="modalQuestion5"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問5</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question" id="q5Question">
            100から7を順番に引いてください。(例: 93, 86, 79...)
          </div>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            data-action="answer" data-score="1" data-next="6">
            93と正答 (1点)
          </button>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            data-action="answer" data-score="2" data-next="6">
            93、86と連続正答 (2点)
          </button>
          <button
            class="btn btn-outline-danger btn-block mb-2"
            data-action="answer" data-score="0" data-next="6">
            不正解 (0点)
          </button>
          <div class="advice alert alert-secondary mt-3">
            「93」を答えられたら「そこからさらに7を引くと？」と質問。
          </div>

          <!-- ワンポイントアドバイス（設問5） -->
          <div class="extra-info">
            <h6>【設問5のポイント：引き算】</h6>
            <p>
              <strong>「93引く7は？」と聞いてもいい？</strong><br />
              ダメです。検査者から「93」という数字を口にすると、作業記憶ではなくただの引き算になってしまいます。
            </p>
            <p>
              <strong>最初に間違えたら？</strong><br />
              そこで打ち切り、次の設問へ進みます。
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 6 ========== -->
  <div
    class="modal fade" data-backdrop="static" data-keyboard="false"
    id="modalQuestion6"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問6</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question" id="q6Question">
            私がこれから言う数字を逆から言ってください。（6・8・2、3・5・2・9）
          </div>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            data-action="answer" data-score="2" data-next="7">
            両方逆唱できた (2点)
          </button>
          <button
            class="btn btn-outline-primary btn-block mb-2"
            data-action="answer" data-score="1" data-next="7">
            3桁のみ逆唱できた (1点)
          </button>
          <button
            class="btn btn-outline-danger btn-block mb-2"
            data-action="answer" data-score="0" data-next="7">
            不正解・失敗 (0点)
          </button>
          <div class="advice alert alert-secondary mt-3">
            まず「6-8-2」と「3-5-2-9」を提示し、両方逆唱できれば2点。難しい場合は3桁だけで判定し、逆唱できれば1点。失敗なら0点です。
          </div>

          <!-- ワンポイントアドバイス（設問6） -->
          <div class="extra-info">
            <h6>【設問6のポイント：数字の逆唱】</h6>
            <p>
              <strong>どのくらいの速さ？</strong><br />
              1秒に1数字くらい。早口だと混乱しやすいので注意しましょう。
            </p>
            <p>
              <strong>4桁が難しい場合は？</strong><br />
              3桁の逆唱に切り替えて判定し、失敗したら打ち切ります。
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 7 ========== -->
  <div
    class="modal fade" data-backdrop="static" data-keyboard="false"
    id="modalQuestion7"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問7</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question" id="q7Question">
            先ほど覚えてもらった言葉をもう一度言ってみてください。
          </div>
          <div class="recall-panel">
            <div class="recall-title">覚えてもらった言葉（検査者用表示）</div>
            <div id="q7WordsDisplay" class="recall-words"></div>
            <div id="q7HintDisplay" class="recall-hint"></div>
          </div>
          <div class="q7-score-grid">
            <div class="q7-score-row">
              <div class="q7-score-word">
                <div class="q7-word-label" data-q7-word="0"></div>
                <div class="q7-word-hint" data-q7-hint="0"></div>
              </div>
              <select class="form-control q7-score-select" data-q7-score="0" name="q7Score[0]">
                <option value="0" {{ old('q7Score.0', '0') == '0' ? 'selected' : '' }}>不正解 (0点)</option>
                <option value="1" {{ old('q7Score.0') == '1' ? 'selected' : '' }}>ヒントあり正解 (1点)</option>
                <option value="2" {{ old('q7Score.0') == '2' ? 'selected' : '' }}>ヒントなし正解 (2点)</option>
              </select>
            </div>
            <div class="q7-score-row">
              <div class="q7-score-word">
                <div class="q7-word-label" data-q7-word="1"></div>
                <div class="q7-word-hint" data-q7-hint="1"></div>
              </div>
              <select class="form-control q7-score-select" data-q7-score="1" name="q7Score[1]">
                <option value="0" {{ old('q7Score.1', '0') == '0' ? 'selected' : '' }}>不正解 (0点)</option>
                <option value="1" {{ old('q7Score.1') == '1' ? 'selected' : '' }}>ヒントあり正解 (1点)</option>
                <option value="2" {{ old('q7Score.1') == '2' ? 'selected' : '' }}>ヒントなし正解 (2点)</option>
              </select>
            </div>
            <div class="q7-score-row">
              <div class="q7-score-word">
                <div class="q7-word-label" data-q7-word="2"></div>
                <div class="q7-word-hint" data-q7-hint="2"></div>
              </div>
              <select class="form-control q7-score-select" data-q7-score="2" name="q7Score[2]">
                <option value="0" {{ old('q7Score.2', '0') == '0' ? 'selected' : '' }}>不正解 (0点)</option>
                <option value="1" {{ old('q7Score.2') == '1' ? 'selected' : '' }}>ヒントあり正解 (1点)</option>
                <option value="2" {{ old('q7Score.2') == '2' ? 'selected' : '' }}>ヒントなし正解 (2点)</option>
              </select>
            </div>
          </div>
          <button
            class="btn btn-primary btn-block mt-3"
            data-action="submit-q7">
            次へ
          </button>
          <div class="advice alert alert-secondary mt-3">
            反応がない場合は、1語ずつ「植物です」などのヒントを出してください。
          </div>
          <!-- ワンポイントアドバイス（設問7） -->
          <div class="extra-info">
            <h6>【設問7のポイント：3つの言葉の遅延再生】</h6>
            <p>
              <strong>ヒントをまとめて与えていい？</strong><br />
              いいえ、一気に「動物と乗り物が…」と言うより、1つずつ区切った方が望ましいです。
            </p>
            <p>
              <strong>思い出すのに時間がかかる場合？</strong><br />
              少し待つ余裕を持ちましょう。焦らず、一呼吸置くと答えが出ることがあります。
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 8 (画像表示→回答) ========== -->
  <div
    class="modal fade" data-backdrop="static" data-keyboard="false"
    id="modalQuestion8"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問8</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <!-- Step1: 画像を提示する画面 -->
          <div id="question8Step1">
            <div class="question" id="q8QuestionStep1">
              これから5つの品物を1つずつ見せます。名前も一緒に読み上げてください。
            </div>
          <div class="q8-current" id="q8FullscreenTarget">
            <div id="q8CurrentName" class="q8-current-name"></div>
            <img id="q8CurrentImage" class="q8-current-image" alt="">
            <div id="q8CurrentFallback" class="q8-placeholder" style="display: none;"></div>
            <div id="q8Progress" class="q8-progress"></div>
            <div class="q8-image-actions">
              <button
                class="btn btn-outline-primary btn-block"
                id="q8FullscreenBtn"
                data-action="q8-fullscreen">
                全画面表示
              </button>
              <button
                class="btn btn-outline-secondary btn-block"
                id="q8ExitFullscreenBtn"
                data-action="q8-exit-fullscreen"
                style="display: none;">
                元のページへ戻る
              </button>
            </div>
          </div>
          <div class="q8-controls">
            <button
              class="btn btn-primary btn-block"
              id="q8NextBtn"
                data-action="q8-next">
                次へ
              </button>
              <button
                class="btn btn-outline-secondary btn-block"
                id="q8RestartBtn"
                data-action="q8-restart">
                もう一度最初から
              </button>
              <div class="q8-retry-note">※もう一度最初から見るボタンは一度しか見て確認出来ないので最後の確認用にご使用ください。</div>
              <div class="helper-note q8-retry-help">※必要なければ確認しなくても問題ありません。</div>
            </div>
          </div>

          <div class="q8-review mt-3">
            <div class="q8-review-title">表示済みの品物（検査者用メモ）</div>
            <div id="q8ShownList" class="q8-shown-list"></div>
          </div>

          <!-- Step2: 回答する画面 -->
          <div id="question8Step2" style="display: none;">
            <div class="question" id="q8QuestionStep2">
              何があったか言ってください。
            </div>
            <p>※正解数を選択してください (0～5)。1つ正解につき1点です。</p>
            <select class="form-control" id="itemCountSelect" name="itemCountSelect">
              <option value="0" {{ old('itemCountSelect', '0') == '0' ? 'selected' : '' }}>0個正解</option>
              <option value="1" {{ old('itemCountSelect') == '1' ? 'selected' : '' }}>1個正解</option>
              <option value="2" {{ old('itemCountSelect') == '2' ? 'selected' : '' }}>2個正解</option>
              <option value="3" {{ old('itemCountSelect') == '3' ? 'selected' : '' }}>3個正解</option>
              <option value="4" {{ old('itemCountSelect') == '4' ? 'selected' : '' }}>4個正解</option>
              <option value="5" {{ old('itemCountSelect') == '5' ? 'selected' : '' }}>5個正解</option>
            </select>
            <div class="advice alert alert-secondary mt-3">
              相互に無関係な物を選ぶのがポイント。関連物は避けてください。
            </div>
            <button
              class="btn btn-primary btn-block mt-3"
              data-action="q8-submit">
              次へ
            </button>
          </div>

          <div class="extra-info mt-3">
            <h6>【設問8のポイント：5つの物品記銘】</h6>
            <p>
              <strong>どんな物がいい？</strong><br />
              馴染みがある物を中心に、時計や鍵など身近な物を選びましょう。
            </p>
            <p>
              <strong>提示するときの注意？</strong><br />
              1枚ずつ名前を読み上げて見せ、最後にまとめて確認します。
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== Modal 9 ========== -->
  <div
    class="modal fade" data-backdrop="static" data-keyboard="false"
    id="modalQuestion9"
    tabindex="-1"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">設問9</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="question" id="q9Question">
            知っている野菜の名前をできるだけ多く言ってください。
          </div>
          <p>該当する個数の区分を選択してください。</p>
          <select class="form-control" id="vegetableSelect" name="vegetableSelect">
            <option value="0" {{ old('vegetableSelect', '0') == '0' ? 'selected' : '' }}>5個以下</option>
            <option value="1" {{ old('vegetableSelect') == '1' ? 'selected' : '' }}>6個</option>
            <option value="2" {{ old('vegetableSelect') == '2' ? 'selected' : '' }}>7個</option>
            <option value="3" {{ old('vegetableSelect') == '3' ? 'selected' : '' }}>8個</option>
            <option value="4" {{ old('vegetableSelect') == '4' ? 'selected' : '' }}>9個</option>
            <option value="5" {{ old('vegetableSelect') == '5' ? 'selected' : '' }}>10個以上</option>
          </select>
          <div class="mt-3">
            <div class="helper-note">※野菜名を最大10個まで記録できます（フリック入力対応）。</div>
            <div class="vegetable-grid">
              <input type="text" class="form-control vegetable-input" inputmode="kana" maxlength="20" placeholder="1つ目" name="vegetables[0]" value="{{ old('vegetables.0') }}">
              <input type="text" class="form-control vegetable-input" inputmode="kana" maxlength="20" placeholder="2つ目" name="vegetables[1]" value="{{ old('vegetables.1') }}">
              <input type="text" class="form-control vegetable-input" inputmode="kana" maxlength="20" placeholder="3つ目" name="vegetables[2]" value="{{ old('vegetables.2') }}">
              <input type="text" class="form-control vegetable-input" inputmode="kana" maxlength="20" placeholder="4つ目" name="vegetables[3]" value="{{ old('vegetables.3') }}">
              <input type="text" class="form-control vegetable-input" inputmode="kana" maxlength="20" placeholder="5つ目" name="vegetables[4]" value="{{ old('vegetables.4') }}">
              <input type="text" class="form-control vegetable-input" inputmode="kana" maxlength="20" placeholder="6つ目" name="vegetables[5]" value="{{ old('vegetables.5') }}">
              <input type="text" class="form-control vegetable-input" inputmode="kana" maxlength="20" placeholder="7つ目" name="vegetables[6]" value="{{ old('vegetables.6') }}">
              <input type="text" class="form-control vegetable-input" inputmode="kana" maxlength="20" placeholder="8つ目" name="vegetables[7]" value="{{ old('vegetables.7') }}">
              <input type="text" class="form-control vegetable-input" inputmode="kana" maxlength="20" placeholder="9つ目" name="vegetables[8]" value="{{ old('vegetables.8') }}">
              <input type="text" class="form-control vegetable-input" inputmode="kana" maxlength="20" placeholder="10こ目" name="vegetables[9]" value="{{ old('vegetables.9') }}">
            </div>
          </div>
          <div class="advice alert alert-secondary mt-3">
            同じ野菜名を繰り返しても記録し、後で重複分を差し引いてください。
          </div>

          <!-- ワンポイントアドバイス（設問9） -->
          <div class="extra-info">
            <h6>【設問9のポイント：野菜の名前】</h6>
            <p>
              <strong>なぜ野菜？</strong><br />
              地域差や性差が比較的少なく、男女問わず答えやすい題材とされています。
            </p>
            <p>
              <strong>平均個数は？</strong><br />
              認知症の方は平均5個前後、健常高齢者だと10個程度が一般的です。
            </p>
          </div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-primary btn-next"
            data-action="submit-q9">
            結果を見る
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ========== 結果モーダル ========== -->
  <div class="modal fade" data-backdrop="static" data-keyboard="false" id="modalResult" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">結果</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 id="scoreDisplay" class="text-center"></h4>
          <p id="resultText" class="text-center font-weight-bold"></p>
        </div>
        <div class="modal-footer">
          <a href="{{ url('/') }}" class="btn btn-outline-primary" id="returnTopLink">トップへ戻る</a>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery & Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script src="{{ asset('JS/hasegawa_quiz.js') }}"></script>
</body>

</html>










