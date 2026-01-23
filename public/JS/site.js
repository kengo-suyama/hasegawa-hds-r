// site.js: サイト共通の JS（花びら演出）
(function() {
  const THEME_KEY = 'careMateTheme';
  const THEME_COLORS = {
    light: '#f2c6d8',
    dark: '#2b1c22'
  };

  function updateThemeColor(theme) {
    const meta = document.querySelector('meta[name="theme-color"]');
    if (!meta) return;
    meta.setAttribute('content', theme === 'dark' ? THEME_COLORS.dark : THEME_COLORS.light);
  }

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
    updateThemeColor(theme);
    const button = document.getElementById('themeToggleBtn');
    if (button) {
      button.textContent = theme === 'dark' ? 'ライトモード' : 'ダークモード';
    }
    if (persist) {
      localStorage.setItem(THEME_KEY, theme);
    }
  }

  function initThemeToggle() {
    applyTheme(getPreferredTheme(), false);
    const button = document.getElementById('themeToggleBtn');
    if (!button) return;
    button.addEventListener('click', () => {
      const isDark = document.body.classList.contains('theme-dark');
      applyTheme(isDark ? 'light' : 'dark');
    });
  }

  function initHomeConsult() {
    const fab = document.getElementById('homeConsultFab');
    const panel = document.getElementById('homeConsultPanel');
    if (!fab || !panel) return;

    const closePanel = () => {
      panel.hidden = true;
      fab.setAttribute('aria-expanded', 'false');
    };

    const openPanel = () => {
      panel.hidden = false;
      fab.setAttribute('aria-expanded', 'true');
    };

    fab.addEventListener('click', (event) => {
      event.stopPropagation();
      if (fab.dataset.dragging === 'true') return;
      if (panel.hidden) {
        openPanel();
      } else {
        closePanel();
      }
    });

    document.addEventListener('click', (event) => {
      if (panel.hidden) return;
      if (panel.contains(event.target) || fab.contains(event.target)) return;
      closePanel();
    });

    initHomeConsultDrag(fab);
  }

  function clampHomeConsultFab(fab, margin = 12) {
    const rect = fab.getBoundingClientRect();
    const maxLeft = window.innerWidth - rect.width - margin;
    const maxTop = window.innerHeight - rect.height - margin;
    const nextLeft = Math.min(maxLeft, Math.max(margin, rect.left));
    const nextTop = Math.min(maxTop, Math.max(margin, rect.top));
    if (rect.left !== nextLeft || rect.top !== nextTop) {
      fab.style.left = `${nextLeft}px`;
      fab.style.top = `${nextTop}px`;
      fab.style.right = 'auto';
      fab.style.bottom = 'auto';
    }
  }

  function initHomeConsultDrag(fab) {
    let dragState = null;

    fab.addEventListener('pointerdown', (event) => {
      if (event.button && event.button !== 0) return;
      const rect = fab.getBoundingClientRect();
      dragState = {
        startX: event.clientX,
        startY: event.clientY,
        startLeft: rect.left,
        startTop: rect.top,
        width: rect.width,
        height: rect.height,
        moved: false
      };
      fab.dataset.dragging = 'false';
      fab.classList.add('is-dragging');
      fab.setPointerCapture(event.pointerId);
      event.preventDefault();
    });

    fab.addEventListener('pointermove', (event) => {
      if (!dragState) return;
      const dx = event.clientX - dragState.startX;
      const dy = event.clientY - dragState.startY;
      if (!dragState.moved && Math.hypot(dx, dy) > 6) {
        dragState.moved = true;
        fab.dataset.dragging = 'true';
      }
      if (!dragState.moved) return;
      const margin = 12;
      const maxLeft = window.innerWidth - dragState.width - margin;
      const maxTop = window.innerHeight - dragState.height - margin;
      const nextLeft = Math.min(maxLeft, Math.max(margin, dragState.startLeft + dx));
      const nextTop = Math.min(maxTop, Math.max(margin, dragState.startTop + dy));
      fab.style.left = `${nextLeft}px`;
      fab.style.top = `${nextTop}px`;
      fab.style.right = 'auto';
      fab.style.bottom = 'auto';
    });

    const endDrag = (event) => {
      if (!dragState) return;
      fab.classList.remove('is-dragging');
      if (dragState.moved) {
        fab.dataset.dragging = 'true';
        setTimeout(() => {
          fab.dataset.dragging = 'false';
        }, 150);
      } else {
        fab.dataset.dragging = 'false';
      }
      dragState = null;
      try {
        fab.releasePointerCapture(event.pointerId);
      } catch (e) {
        // noop
      }
    };

    fab.addEventListener('pointerup', endDrag);
    fab.addEventListener('pointercancel', endDrag);

    window.addEventListener('resize', () => {
      if (fab.style.left || fab.style.top) {
        clampHomeConsultFab(fab);
      }
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

  function registerServiceWorker() {
    if (!('serviceWorker' in navigator)) return;
    navigator.serviceWorker.register('/service-worker.js').catch(() => {
      // noop
    });
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
    initHomeConsult();
    registerServiceWorker();
  });
  window.addEventListener('resize', handleResize);
  window.addEventListener('online', updateNetworkStatus);
  window.addEventListener('offline', updateNetworkStatus);
})();
