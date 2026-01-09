// site.js: サイト共通の JS（花びら演出）
(function() {
  const THEME_KEY = 'careMateTheme';

  function getPreferredTheme() {
    const saved = localStorage.getItem(THEME_KEY);
    if (saved === 'dark' || saved === 'light') return saved;
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
      ? 'dark'
      : 'light';
  }

  function applyTheme(theme, persist = true) {
    const root = document.documentElement;
    root.classList.toggle('theme-dark', theme === 'dark');
    root.classList.toggle('theme-light', theme === 'light');
    if (document.body) {
      document.body.classList.toggle('theme-dark', theme === 'dark');
      document.body.classList.toggle('theme-light', theme === 'light');
    }
    const button = document.getElementById('themeToggleBtn');
    if (button) {
      button.textContent = theme === 'dark' ? 'ライトモード' : 'ダークモード';
    }
    if (persist) {
      localStorage.setItem(THEME_KEY, theme);
    }
  }

  function initThemeToggle() {
    const button = document.getElementById('themeToggleBtn');
    if (!button) return;
    applyTheme(getPreferredTheme(), false);
    button.addEventListener('click', () => {
      const isDark = document.body.classList.contains('theme-dark');
      applyTheme(isDark ? 'light' : 'dark');
    });
  }

  function getPetalScale() {
    const base = Math.min(window.innerWidth || 0, window.innerHeight || 0);
    if (!base) return 1;
    return Math.max(0.7, Math.min(base / 760, 1.2));
  }

  function getPetalCount() {
    const width = Math.min(window.innerWidth || 0, window.screen?.width || window.innerWidth || 0);
    if (width < 480) return 22;
    if (width < 900) return 32;
    return 42;
  }

  function initPetalFall() {
    const layer = document.getElementById('petalLayer');
    if (!layer) return;
    layer.innerHTML = '';

    const petalCount = getPetalCount();
    for (let i = 0; i < petalCount; i += 1) {
      const petal = document.createElement('div');
      petal.className = 'petal';

      const sway = document.createElement('span');
      sway.className = 'petal-sway';

      const sizeScale = getPetalScale();
      const size = (16 + Math.random() * 28) * sizeScale;
      const fallDuration = 9 + Math.random() * 14;
      const swayDuration = 2.6 + Math.random() * 5.2;
      const startX = Math.random() * 100;
      const fallX = -40 + Math.random() * 80;
      const swayX = -90 + Math.random() * 180;
      const rotate = 40 + Math.random() * 140;
      const rotateEnd = rotate + 40 + Math.random() * 80;
      const opacity = 0.65 + Math.random() * 0.3;

      const hue = 342 + Math.random() * 14;
      const saturation = 35 + Math.random() * 35;
      const lightness = 78 + Math.random() * 16;
      const color = `hsl(${hue}, ${saturation}%, ${lightness}%)`;
      const highlight = `hsl(${hue}, ${saturation}%, ${Math.min(lightness + 10, 92)}%)`;
      const shadow = `hsl(${hue}, ${saturation}%, ${Math.max(lightness - 12, 50)}%)`;

      petal.style.left = `${startX}vw`;
      petal.style.setProperty('--fall-duration', `${fallDuration}s`);
      petal.style.setProperty('--fall-x', `${fallX}vw`);
      petal.style.animationDelay = `${-Math.random() * fallDuration}s`;

      sway.style.setProperty('--petal-size', `${size}px`);
      sway.style.setProperty('--petal-color', color);
      sway.style.setProperty('--petal-highlight', highlight);
      sway.style.setProperty('--petal-shadow', shadow);
      sway.style.setProperty('--sway-duration', `${swayDuration}s`);
      sway.style.setProperty('--sway-x', `${swayX}px`);
      sway.style.setProperty('--sway-rotate', `${rotate}deg`);
      sway.style.setProperty('--sway-rotate-end', `${rotateEnd}deg`);
      sway.style.setProperty('--petal-opacity', `${opacity}`);
      sway.style.animationDelay = `${-Math.random() * swayDuration}s`;

      petal.appendChild(sway);
      layer.appendChild(petal);
    }
  }

  function updateNetworkStatus() {
    const banner = document.getElementById('networkStatus');
    if (!banner) return;
    banner.style.display = navigator.onLine ? 'none' : 'block';
  }

  function initLoadingOverlay() {
    const overlay = document.getElementById('globalLoading');
    if (!overlay) return;
    const timer = setTimeout(() => {
      overlay.classList.add('is-visible');
    }, 300);
    window.addEventListener('load', () => {
      clearTimeout(timer);
      overlay.classList.remove('is-visible');
    }, { once: true });
  }

  let resizeTimer = null;
  function handleResize() {
    if (resizeTimer) clearTimeout(resizeTimer);
    resizeTimer = setTimeout(initPetalFall, 200);
  }

  document.addEventListener('DOMContentLoaded', () => {
    initPetalFall();
    initLoadingOverlay();
    updateNetworkStatus();
    initThemeToggle();
  });
  window.addEventListener('resize', handleResize);
  window.addEventListener('online', updateNetworkStatus);
  window.addEventListener('offline', updateNetworkStatus);
})();
