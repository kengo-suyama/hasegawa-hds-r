// site.js: サイト共通の JS（index.js を統合）

// --------------------------------------------------
// 桜エフェクト (Canvas)
// - モバイル対応: DPR（デバイスピクセル比）を考慮したリサイズ
// - ページ非表示時はアニメーションを停止してバッテリー節約
// - 画面幅が狭い場合は花びらの数を削減
// - Canvas 未サポート時はフォールバック（何もしない）
// --------------------------------------------------
(function() {
  const canvas = document.getElementById('sakura-canvas');
  // Canvas が無ければ何もしない（フォールバック）
  if (!canvas || !canvas.getContext) return;
  const ctx = canvas.getContext('2d');

  // デバイス性能に応じて花びら数を調整
  const basePetalCount = 40; // デスクトップ向け基準
  const smallScreenPetalCount = 18; // モバイル向け

  // DPR (devicePixelRatio) によるスケーリング
  function setCanvasSize() {
    const dpr = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = Math.floor(window.innerWidth * dpr);
    canvas.height = Math.floor(window.innerHeight * dpr);
    canvas.style.width = window.innerWidth + 'px';
    canvas.style.height = window.innerHeight + 'px';
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0); // DPIスケールを設定
  }

  // ペタルの数は画面幅で調整（狭い画面では減らす）
  function getPetalCount() {
    try {
      const w = Math.min(window.innerWidth, window.screen.width || window.innerWidth);
      if (w < 480) return smallScreenPetalCount;
      if (w < 900) return Math.round(basePetalCount * 0.7);
      return basePetalCount;
    } catch (e) {
      return basePetalCount;
    }
  }

  let petals = [];
  const colors = ['#ffb7c5', '#ffe4ee', '#ffb7c5', '#fff0f5'];

  function random(min, max) { return Math.random() * (max - min) + min; }

  function createPetal() {
    return {
      x: random(0, canvas.width),
      y: random(-canvas.height, 0),
      r: random(6, 18),
      speed: random(0.6, 2.2),
      drift: random(-0.6, 0.6),
      angle: random(0, 2 * Math.PI),
      color: colors[Math.floor(Math.random() * colors.length)]
    };
  }

  function drawPetal(p) {
    ctx.save();
    ctx.translate(p.x, p.y);
    ctx.rotate(p.angle);
    ctx.beginPath();
    // 花びらをベジエで描画（軽量）
    ctx.moveTo(0, 0);
    ctx.bezierCurveTo(-p.r/2, -p.r/2, -p.r, p.r/2, 0, p.r);
    ctx.bezierCurveTo(p.r, p.r/2, p.r/2, -p.r/2, 0, 0);
    ctx.closePath();
    ctx.fillStyle = p.color;
    ctx.globalAlpha = 0.8;
    // モバイルではシャドウを下げてパフォーマンス確保
    ctx.shadowColor = p.color;
    ctx.shadowBlur = (window.innerWidth < 480) ? 2 : 8;
    ctx.fill();
    ctx.restore();
  }

  function updatePetal(p) {
    p.y += p.speed;
    p.x += p.drift;
    p.angle += random(-0.02, 0.02);
    if (p.y > canvas.height + 20 || p.x < -40 || p.x > canvas.width + 40) {
      Object.assign(p, createPetal());
      p.y = -20;
    }
  }

  let rafId = null;
  function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (let i = 0; i < petals.length; i++) {
      const p = petals[i];
      drawPetal(p);
      updatePetal(p);
    }
    rafId = requestAnimationFrame(animate);
  }

  // ページが非表示のときはアニメを止める
  function handleVisibility() {
    if (document.hidden) {
      if (rafId) cancelAnimationFrame(rafId);
      rafId = null;
    } else {
      if (!rafId) animate();
    }
  }

  // 初期化
  function init() {
    setCanvasSize();
    const count = getPetalCount();
    petals = Array.from({length: count}, createPetal);
    // 既に表示中ならアニメ開始
    if (!document.hidden) animate();
  }

  // リサイズ/可視性のイベント
  window.addEventListener('resize', function() {
    // resize の度にcanvasを再設定し、花びら数を調整
    setCanvasSize();
    const newCount = getPetalCount();
    if (newCount !== petals.length) {
      petals = Array.from({length: newCount}, createPetal);
    }
  });
  document.addEventListener('visibilitychange', handleVisibility);

  // タッチ操作中はパフォーマンスのため花びら数を一時的に減らす
  let touchTimeout = null;
  function reduceForTouch() {
    if (touchTimeout) clearTimeout(touchTimeout);
    const original = petals.length;
    if (original > 10) petals = petals.slice(0, Math.max(8, Math.round(original / 3)));
    touchTimeout = setTimeout(function() {
      // touch終了後、元に戻す
      petals = Array.from({length: getPetalCount()}, createPetal);
    }, 1500);
  }
  window.addEventListener('touchstart', reduceForTouch, {passive:true});

  // 初期化呼び出し
  init();
})();

// 追加の初期化や共通処理があればここに追記
