// index.js: 桜のCanvasエフェクトと初期化コード
(function() {
  const canvas = document.getElementById('sakura-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  let petals = [];
  const petalCount = 40;
  const colors = ['#ffb7c5', '#ffe4ee', '#ffb7c5', '#fff0f5'];

  function resize() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  }
  window.addEventListener('resize', resize);
  resize();

  function random(min, max) {
    return Math.random() * (max - min) + min;
  }
  function createPetal() {
    return {
      x: random(0, canvas.width),
      y: random(-canvas.height, 0),
      r: random(8, 18),
      speed: random(1, 2.5),
      drift: random(-0.7, 0.7),
      angle: random(0, 2 * Math.PI),
      color: colors[Math.floor(Math.random() * colors.length)]
    };
  }
  function drawPetal(p) {
    ctx.save();
    ctx.translate(p.x, p.y);
    ctx.rotate(p.angle);
    ctx.beginPath();
    ctx.moveTo(0, 0);
    ctx.bezierCurveTo(-p.r/2, -p.r/2, -p.r, p.r/2, 0, p.r);
    ctx.bezierCurveTo(p.r, p.r/2, p.r/2, -p.r/2, 0, 0);
    ctx.closePath();
    ctx.fillStyle = p.color;
    ctx.globalAlpha = 0.7;
    ctx.shadowColor = '#ffb7c5';
    ctx.shadowBlur = 8;
    ctx.fill();
    ctx.restore();
  }
  function updatePetal(p) {
    p.y += p.speed;
    p.x += p.drift;
    p.angle += random(-0.01, 0.01);
    if (p.y > canvas.height + 20 || p.x < -20 || p.x > canvas.width + 20) {
      Object.assign(p, createPetal());
      p.y = -20;
    }
  }
  function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (let p of petals) {
      drawPetal(p);
      updatePetal(p);
    }
    requestAnimationFrame(animate);
  }
  petals = Array.from({length: petalCount}, createPetal);
  animate();
})();
