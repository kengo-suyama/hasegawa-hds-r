    // ==============================
    // 桜の花びら演出
    // ==============================
    function getPetalScale() {
      const base = Math.min(window.innerWidth || 0, window.innerHeight || 0);
      if (!base) return 1;
      return Math.max(0.7, Math.min(base / 760, 1.2));
    }

    function getPetalCount() {
      const width = Math.min(window.innerWidth || 0, window.screen?.width || window.innerWidth || 0);
      if (width < 480) return 28;
      if (width < 900) return 38;
      return 46;
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

    function setLoading(isVisible, message) {
      const overlay = document.getElementById('globalLoading');
      if (!overlay) return;
      if (message) {
        const text = overlay.querySelector('.loading-text');
        if (text) text.textContent = message;
      }
      overlay.classList.toggle('is-visible', Boolean(isVisible));
    }

    function scheduleInitialLoading() {
      const overlay = document.getElementById('globalLoading');
      if (!overlay) return;
      const timer = setTimeout(() => {
        setLoading(true, '読み込み中...');
      }, 300);
      window.addEventListener('load', () => {
        clearTimeout(timer);
        setLoading(false);
      }, { once: true });
    }

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

    function registerServiceWorker() {
      if (!('serviceWorker' in navigator)) return;
      navigator.serviceWorker.register('/service-worker.js').catch(() => {
        // noop
      });
    }

    $(function() {
      initPetalFall();
      scheduleInitialLoading();
      updateNetworkStatus();
      initThemeToggle();
      registerServiceWorker();
      let resizeTimer = null;
      $(window).on('resize', function() {
        if (resizeTimer) clearTimeout(resizeTimer);
        resizeTimer = setTimeout(initPetalFall, 200);
      });
    });

    window.addEventListener('online', updateNetworkStatus);
    window.addEventListener('offline', updateNetworkStatus);

    // ------------------------------
    // 西暦年プルダウンを初期化
    // ------------------------------
    function populateYears(startYear = 1900) {
      const yearSelect = document.getElementById('birthYear');
      yearSelect.innerHTML = '';
      const current = new Date().getFullYear();
      for (let y = current; y >= startYear; y--) {
        const opt = document.createElement('option');
        opt.value = y;
        opt.textContent = y + '年';
        yearSelect.appendChild(opt);
      }
      const preset = yearSelect.dataset.old;
      if (preset) {
        yearSelect.value = preset;
      }
    }

    // ページロードで西暦を生成
    document.addEventListener('DOMContentLoaded', function() {
      populateYears(1900);
      initQ4WordSet();
      const restored = restoreState();
      if (!restored) {
        resetQ8Sequence();
      }
      updateStartButtonState();
      setConsultOpen(false);
      initConsultFabDrag();
    });

    // 生年月日不明フラグ
    let birthUnknown = false;

    // ------------------------------
    // 生年月日が不明瞭ボタンの処理
    // ------------------------------
    function markBirthUnknown() {
      birthUnknown = true;
      // フラッシュメッセージを表示
      const flash = document.getElementById('birthUnknownFlash');
      if (flash) {
        flash.innerHTML = '<strong>注意：</strong>生年月日が不明瞭なため、設問1は自動的に「0点」として扱います。保険証等で確認できる場合は必ず確認してください。<br><small>いきさつ: ご家族や保険証で年齢が確認できない場合、正確な年齢を問うことが困難なため、検査の公平性を保つためにこのオプションを設けています。</small><div class="mt-2"><button type="button" class="btn btn-outline-secondary btn-sm" data-action="close-birth-unknown">閉じる</button></div>';
        flash.style.display = 'block';
      }

      // 検査開始ボタンを有効化して、年齢表示をダミーにセット（0扱い）
      correctAge = null;
      document.getElementById('calculatedAgeArea').style.display = 'none';
      updateStartButtonState();
      saveState();
    }

    // ==============================
    // スコア管理用変数
    // ==============================
    let score = 0;
    let correctAge = null; // 計算した年齢を保持

    const QUIZ_STATE_KEY = 'careMateQuizState';
    let activeModalId = null;
    let hasRestoredState = false;
    let isRestoring = false;
    let lastModalId = null;

    function showBackNotice() {
      const notice = document.getElementById('backNotice');
      if (notice) notice.style.display = 'block';
    }

    function hideBackNotice() {
      const notice = document.getElementById('backNotice');
      if (notice) notice.style.display = 'none';
    }

    function isPrecheckConfirmed() {
      const checkbox = document.getElementById('precheckConfirm');
      return checkbox ? checkbox.checked : false;
    }

    function updateStartButtonState() {
      const startBtn = document.getElementById('startQuizBtn');
      if (!startBtn) return;
      const isReady = (birthUnknown || correctAge !== null) && isPrecheckConfirmed();
      startBtn.disabled = !isReady;
    }

    let consultHideTimer = null;

    function setConsultOpen(isOpen) {
      const sheet = document.getElementById('consultSheet');
      const backdrop = document.getElementById('consultBackdrop');
      const button = document.getElementById('consultFab');
      const openState = Boolean(isOpen);

      if (consultHideTimer) {
        clearTimeout(consultHideTimer);
        consultHideTimer = null;
      }

      if (openState) {
        if (sheet) sheet.hidden = false;
        if (backdrop) backdrop.hidden = false;
        requestAnimationFrame(() => {
          document.body.classList.add('consult-open');
        });
      } else {
        document.body.classList.remove('consult-open');
        const shouldDelayHide = (sheet && !sheet.hidden) || (backdrop && !backdrop.hidden);
        if (shouldDelayHide) {
          consultHideTimer = setTimeout(() => {
            if (sheet) sheet.hidden = true;
            if (backdrop) backdrop.hidden = true;
          }, 260);
        } else {
          if (sheet) sheet.hidden = true;
          if (backdrop) backdrop.hidden = true;
        }
      }

      if (sheet) sheet.setAttribute('aria-hidden', openState ? 'false' : 'true');
      if (backdrop) backdrop.setAttribute('aria-hidden', openState ? 'false' : 'true');
      if (button) button.setAttribute('aria-expanded', openState ? 'true' : 'false');
    }

    function clampConsultFab(fab, margin = 12) {
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

    function initConsultFabDrag() {
      const fab = document.getElementById('consultFab');
      if (!fab) return;
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
          clampConsultFab(fab);
        }
      });
    }

    function exitApp() {
      clearStoredState();
      hideBackNotice();

      const canExitNavigator = typeof navigator !== 'undefined'
        && navigator.app
        && typeof navigator.app.exitApp === 'function';
      if (canExitNavigator) {
        navigator.app.exitApp();
        return;
      }

      const capacitorExit = window.Capacitor
        && window.Capacitor.Plugins
        && window.Capacitor.Plugins.App
        && window.Capacitor.Plugins.App.exitApp;
      if (typeof capacitorExit === 'function') {
        capacitorExit();
        return;
      }

      try {
        window.close();
      } catch (e) {
        // noop
      }

      setTimeout(() => {
        if (!document.hidden) {
          returnToTop();
        }
      }, 200);
    }

    function readStoredState() {
      try {
        const raw = sessionStorage.getItem(QUIZ_STATE_KEY);
        return raw ? JSON.parse(raw) : null;
      } catch (e) {
        return null;
      }
    }

    function writeStoredState(state) {
      try {
        sessionStorage.setItem(QUIZ_STATE_KEY, JSON.stringify(state));
      } catch (e) {
        // noop
      }
    }

    function clearStoredState() {
      try {
        sessionStorage.removeItem(QUIZ_STATE_KEY);
      } catch (e) {
        // noop
      }
    }

    function collectFormValues() {
      const values = {};
      const fields = document.querySelectorAll('input, select, textarea');
      fields.forEach((field) => {
        const key = field.name || field.id;
        if (!key) return;
        if (field.type === 'radio') {
          if (field.checked) values[key] = field.value;
          return;
        }
        if (field.type === 'checkbox') {
          values[key] = field.checked;
          return;
        }
        values[key] = field.value;
      });
      return values;
    }

    function applyFormValues(values) {
      if (!values) return;
      Object.keys(values).forEach((key) => {
        const value = values[key];
        const elements = document.getElementsByName(key);
        if (elements && elements.length) {
          Array.from(elements).forEach((el) => {
            if (el.type === 'radio') {
              el.checked = el.value === value;
            } else if (el.type === 'checkbox') {
              el.checked = Boolean(value);
            } else {
              el.value = value;
            }
          });
          return;
        }
        const byId = document.getElementById(key);
        if (!byId) return;
        if (byId.type === 'checkbox') {
          byId.checked = Boolean(value);
        } else {
          byId.value = value;
        }
      });
    }

    function applyOldDefaults() {
      const oldFields = document.querySelectorAll('[data-old]');
      oldFields.forEach((field) => {
        const value = field.dataset.old;
        if (!value) return;
        field.value = value;
      });
    }

    function buildStateSnapshot() {
      const step2 = document.getElementById('question8Step2');
      const intro = document.getElementById('introSection');
      return {
        score,
        correctAge,
        birthUnknown,
        q4SelectedSet,
        q8Index,
        q8RetryUsed,
        q8Initialized,
        q8Order: q8Items.map((item) => item.file),
        q8Shown: Array.from(q8Shown),
        q8Step: step2 && step2.style.display !== 'none' ? 2 : 1,
        introHidden: intro ? intro.style.display === 'none' : false,
        currentModalId: activeModalId,
        formValues: collectFormValues()
      };
    }

    function saveState() {
      if (isRestoring) return;
      writeStoredState(buildStateSnapshot());
    }

    function renderQ1SkipNotice() {
      const notice = document.getElementById('q1SkipNotice');
      if (notice) {
        notice.innerHTML = '<div class="alert alert-warning"><strong>注意:</strong>生年月日が不明瞭なため、設問１は自動的に「０」として扱いました。保険証等で確認できる場合は必ず確認してください。</div>';
      }
    }

    function restoreQ8State(state) {
      if (!state) return;
      if (Array.isArray(state.q8Order) && state.q8Order.length) {
        const mapped = state.q8Order.map((file) => q8ItemsBase.find((item) => item.file === file)).filter(Boolean);
        q8Items = mapped.length ? mapped : q8ItemsBase.slice();
      } else {
        q8Items = q8ItemsBase.slice();
      }
      q8Initialized = Boolean(state.q8Initialized);
      if (!q8Initialized && q8Items.length) {
        q8Initialized = true;
      }
      q8Index = Number.isInteger(state.q8Index) ? state.q8Index : 0;
      q8RetryUsed = Boolean(state.q8RetryUsed);
      q8Shown = new Set(state.q8Shown || []);

      const list = document.getElementById('q8ShownList');
      if (list) list.innerHTML = '';
      q8Items.forEach((item) => {
        if (q8Shown.has(item.file)) {
          addQ8ShownItem(item, true);
        }
      });

      const step1 = document.getElementById('question8Step1');
      const step2 = document.getElementById('question8Step2');
      if (state.q8Step === 2) {
        if (step1) step1.style.display = 'none';
        if (step2) step2.style.display = 'block';
      } else {
        if (step1) step1.style.display = 'block';
        if (step2) step2.style.display = 'none';
      }

      updateQ8RestartButton();
      updateQ8NextButton();
      updateQ8Progress();
      renderQ8Current();
    }

    function restoreState() {
      const state = readStoredState();
      if (!state) {
        applyOldDefaults();
        return false;
      }
      if (state.currentModalId === 'modalResult') {
        clearStoredState();
        applyOldDefaults();
        return false;
      }
      isRestoring = true;
      score = Number.isFinite(state.score) ? state.score : 0;
      correctAge = state.correctAge ?? null;
      birthUnknown = Boolean(state.birthUnknown);
      if (birthUnknown) {
        markBirthUnknown();
      } else {
        const flash = document.getElementById('birthUnknownFlash');
        if (flash) {
          flash.style.display = 'none';
          flash.innerHTML = '';
        }
      }

      applyFormValues(state.formValues);

      if (correctAge !== null) {
        const calcSpan = document.getElementById('calculatedAgeSpan');
        if (calcSpan) calcSpan.textContent = String(correctAge);
        const calcArea = document.getElementById('calculatedAgeArea');
        if (calcArea && !birthUnknown) calcArea.style.display = 'block';
        const display = document.getElementById('calculatedAgeDisplay');
        if (display) display.textContent = String(correctAge);
      }

      if (state.q4SelectedSet) {
        q4SelectedSet = state.q4SelectedSet;
        const selectedRadio = document.querySelector(`input[name="q4WordSet"][value="${q4SelectedSet}"]`);
        if (selectedRadio) selectedRadio.checked = true;
        updateQ4Display();
      }

      if (state.introHidden) {
        const intro = document.getElementById('introSection');
        if (intro) intro.style.display = 'none';
        if (birthUnknown) {
          renderQ1SkipNotice();
        }
      }

      const startBtn = document.getElementById('startQuizBtn');
      if (startBtn) {
        updateStartButtonState();
      }

      restoreQ8State(state);

      if (state.currentModalId) {
        activeModalId = state.currentModalId;
        $('#' + state.currentModalId).modal('show');
      }

      hasRestoredState = true;
      isRestoring = false;
      return true;
    }

    // ==============================
    // 設問3のヒント（カウント表示）
    // ==============================
    let q3HintTimer = null;
    let q3CountdownTimer = null;

    function scheduleQ3Hint() {
      clearQ3Hint();
      const hint = document.getElementById('q3Hint');
      const countdown = document.getElementById('q3HintCountdown');
      let remaining = 5;

      if (countdown) {
        countdown.textContent = `ヒントまで ${remaining} 秒`;
        countdown.style.display = 'block';
      }
      if (hint) hint.style.display = 'none';

      q3CountdownTimer = setInterval(() => {
        remaining -= 1;
        if (remaining > 0 && countdown) {
          countdown.textContent = `ヒントまで ${remaining} 秒`;
        }
      }, 1000);

      q3HintTimer = setTimeout(() => {
        if (countdown) countdown.style.display = 'none';
        if (hint) hint.style.display = 'block';
        if (q3CountdownTimer) {
          clearInterval(q3CountdownTimer);
          q3CountdownTimer = null;
        }
      }, 5000);
    }

    function clearQ3Hint() {
      if (q3HintTimer) {
        clearTimeout(q3HintTimer);
        q3HintTimer = null;
      }
      if (q3CountdownTimer) {
        clearInterval(q3CountdownTimer);
        q3CountdownTimer = null;
      }
      const hint = document.getElementById('q3Hint');
      if (hint) hint.style.display = 'none';
      const countdown = document.getElementById('q3HintCountdown');
      if (countdown) countdown.style.display = 'none';
    }
    // ==============================
    // 設問4/7: 記銘ワード管理
    // ==============================
    const q4WordSets = {
      setA: {
        words: ['桜', '猫', '電車'],
        categories: ['植物', '動物', '乗り物']
      },
      setB: {
        words: ['梅', '犬', '自動車'],
        categories: ['植物', '動物', '乗り物']
      }
    };
    let q4SelectedSet = 'setA';

    function getQ4Set() {
      return q4WordSets[q4SelectedSet] || q4WordSets.setA;
    }

    function updateQ4Display() {
      const set = getQ4Set();
      const display = document.getElementById('q4WordsDisplay');
      if (display) display.textContent = set.words.join(' ・ ');
      updateQ7Display();
    }

    function updateQ7Display() {
      const set = getQ4Set();
      const wordsEl = document.getElementById('q7WordsDisplay');
      if (wordsEl) wordsEl.textContent = set.words.join(' ・ ');
      const hintEl = document.getElementById('q7HintDisplay');
      if (hintEl) hintEl.textContent = `ヒント: ${set.categories.join(' / ')}`;
      updateQ7ScoreRows();
    }

    function updateQ7ScoreRows() {
      const set = getQ4Set();
      const wordEls = document.querySelectorAll('[data-q7-word]');
      wordEls.forEach((el, index) => {
        el.textContent = set.words[index] || '';
      });
      const hintEls = document.querySelectorAll('[data-q7-hint]');
      hintEls.forEach((el, index) => {
        const category = set.categories[index] || '';
        el.textContent = category ? `ヒント: ${category}` : '';
      });
    }

    function resetQ7Scores() {
      const selects = document.querySelectorAll('[data-q7-score]');
      selects.forEach((select) => {
        select.value = '0';
      });
    }

    function submitQuestion7() {
      let points = 0;
      const selects = document.querySelectorAll('[data-q7-score]');
      selects.forEach((select) => {
        const value = parseInt(select.value, 10);
        points += isNaN(value) ? 0 : value;
      });
      score += points;

      $('#modalQuestion7').modal('hide');
      $('#modalQuestion8').modal('show');
      saveState();
    }

    function initQ4WordSet() {
      const inputs = document.querySelectorAll('input[name="q4WordSet"]');
      if (!inputs.length) return;
      inputs.forEach((input) => {
        input.addEventListener('change', () => {
          q4SelectedSet = input.value;
          updateQ4Display();
        });
      });
      const checked = document.querySelector('input[name="q4WordSet"]:checked');
      if (checked) q4SelectedSet = checked.value;
      updateQ4Display();
    }

    // ==============================
    // 生年月日→年齢を計算
    // ==============================

    function calculateAge() {
      // 生年月日不明フラグが立っている場合はここで処理せずに早期return
      if (birthUnknown) {
        // 明示的に計算をスキップ
        correctAge = null;
        document.getElementById('calculatedAgeArea').style.display = 'none';
        updateStartButtonState();
        return;
      }

      // 西暦年取得（select の値は西暦）
      const y = parseInt(document.getElementById('birthYear').value, 10);
      const m = parseInt(document.getElementById('birthMonth').value, 10);
      const d = parseInt(document.getElementById('birthDay').value, 10);

      // 数値チェック
      if (isNaN(y) || isNaN(m) || isNaN(d)) {
        alert('生年月日を正しく入力してください。');
        return;
      }

      // 範囲チェック
      if (y < 1900 || y > 2100 || m < 1 || m > 12 || d < 1 || d > 31) {
        alert('生年月日の範囲が不正です。');
        return;
      }

      // 実在日付チェック
      const birthDate = new Date(y, m - 1, d);
      if (isNaN(birthDate.getTime())) {
        alert('実在しない日付のようです。正しく入力してください。');
        return;
      }

      const now = new Date();
      let age = now.getFullYear() - birthDate.getFullYear();
      const thisYearBirthday = new Date(
        now.getFullYear(),
        birthDate.getMonth(),
        birthDate.getDate()
      );
      if (now < thisYearBirthday) {
        age--;
      }
      if (age > 120) {
        alert('入力された生年月日から算出された年齢が120歳を超えています。');
        return;
      } else if (age < 0) {
        alert('未来の生年月日です。正しい値を入力してください。');
        return;
      }
      correctAge = age;
      document.getElementById('calculatedAgeSpan').textContent = String(age);
      document.getElementById('calculatedAgeArea').style.display = 'block';
      updateStartButtonState();
      saveState();
    }

    // ==============================
    // 検査開始
    // ==============================
    function startQuiz() {
      clearStoredState();
      hasRestoredState = false;
      activeModalId = null;
      lastModalId = null;
      hideBackNotice();
      // スコア等を初期化してイントロ画面を隠す
      score = 0;
      q8Initialized = false;
      q8RetryUsed = false;
      // イントロ画面を隠す
      document.getElementById('introSection').style.display = 'none';

      if (birthUnknown) {
        renderQ1SkipNotice();
        $('#modalQuestion2').modal('show');
        saveState();
        return;
      }

      // 設問1を開く
      $('#modalQuestion1').modal('show');

      // 設問1モーダルの文言に"計算した年齢"を表示
      if (correctAge !== null) {
        const display = document.getElementById('calculatedAgeDisplay');
        if (display) {
          display.textContent = String(correctAge);
        }
      }
      saveState();
    }

    // ==============================
    // handleAnswer(加点, 次のモーダル番号)
    // ==============================
    function handleAnswer(scoreToAdd, nextModalNum) {
      score += scoreToAdd;
      $('#modalQuestion' + (nextModalNum - 1)).modal('hide');
      $('#modalQuestion' + nextModalNum).modal('show');
      saveState();
    }

    // ==============================
    // submitQuestion2: 設問2の処理
    // ==============================
    function submitQuestion2() {
      // year / month / day / weekday
      const yearScore = parseInt(document.getElementById('yearSelect').value, 10);
      const monthScore = parseInt(document.getElementById('monthSelect').value, 10);
      const dayScore = parseInt(document.getElementById('daySelect').value, 10);
      const weekdayScore = parseInt(document.getElementById('weekdaySelect').value, 10);

      score += (yearScore + monthScore + dayScore + weekdayScore);

      $('#modalQuestion2').modal('hide');
      $('#modalQuestion3').modal('show');
      saveState();
    }

            // ==============================
    // submitQuestion8: 設問8の処理
    // ==============================
    const q8ItemsBase = [
      { name: '時計', file: 'watch.png' },
      { name: '鍵', file: 'key.png' },
      { name: '眼鏡', file: 'glasses.png' },
      { name: 'ペン', file: 'pen.png' },
      { name: 'ハサミ', file: 'scissors.png' }
    ];
    let q8Items = [];
    let q8Index = 0;
    let q8Shown = new Set();
    let q8RetryUsed = false;
    let q8Initialized = false;
    let q8FallbackFullscreenActive = false;
    let isPopStateClose = false;

    function shuffleQ8Items(items) {
      const copy = items.slice();
      for (let i = copy.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        const temp = copy[i];
        copy[i] = copy[j];
        copy[j] = temp;
      }
      return copy;
    }

    function ensureQ8Order() {
      if (!q8Initialized) {
        q8Items = shuffleQ8Items(q8ItemsBase);
        q8RetryUsed = false;
        q8Initialized = true;
      }
    }

    function updateQ8Progress() {
      const progress = document.getElementById('q8Progress');
      if (progress) {
        progress.textContent = `表示中: ${q8Index + 1} / ${q8Items.length}`;
      }
    }

    function updateQ8NextButton() {
      const btn = document.getElementById('q8NextBtn');
      if (!btn) return;
      if (q8Index >= q8Items.length - 1) {
        btn.textContent = '回答へ進む';
      } else {
        btn.textContent = '次へ';
      }
    }

    function updateQ8RestartButton() {
      const btn = document.getElementById('q8RestartBtn');
      if (btn) btn.disabled = q8RetryUsed;
    }

    function updateQ8FullscreenButtons() {
      const enterBtn = document.getElementById('q8FullscreenBtn');
      const exitBtn = document.getElementById('q8ExitFullscreenBtn');
      const isActive = Boolean(document.fullscreenElement) || q8FallbackFullscreenActive;
      if (enterBtn) enterBtn.style.display = isActive ? 'none' : 'block';
      if (exitBtn) exitBtn.style.display = isActive ? 'block' : 'none';
    }

    function setQ8FullscreenFallback(isActive) {
      q8FallbackFullscreenActive = isActive;
      const target = document.getElementById('q8FullscreenTarget');
      if (target) target.classList.toggle('q8-fullscreen-fallback', isActive);
      document.body.classList.toggle('q8-fallback-active', isActive);
      updateQ8FullscreenButtons();
    }

    function enterQ8Fullscreen() {
      const target = document.getElementById('q8FullscreenTarget');
      if (!target || document.fullscreenElement || q8FallbackFullscreenActive) return;
      if (document.fullscreenEnabled && target.requestFullscreen) {
        target.requestFullscreen().catch(() => {
          setQ8FullscreenFallback(true);
        });
        return;
      }
      setQ8FullscreenFallback(true);
    }

    function exitQ8Fullscreen() {
      if (document.fullscreenElement && document.exitFullscreen) {
        document.exitFullscreen();
      }
      if (q8FallbackFullscreenActive) {
        setQ8FullscreenFallback(false);
      }
    }

    let q8LoadingTimer = null;

    function showQ8Loading() {
      if (q8LoadingTimer) {
        clearTimeout(q8LoadingTimer);
      }
      q8LoadingTimer = setTimeout(() => {
        setLoading(true, '画像を読み込み中...');
      }, 200);
    }

    function hideQ8Loading() {
      if (q8LoadingTimer) {
        clearTimeout(q8LoadingTimer);
        q8LoadingTimer = null;
      }
      setLoading(false);
    }

    function renderQ8Current() {
      ensureQ8Order();
      const item = q8Items[q8Index];
      const nameEl = document.getElementById('q8CurrentName');
      const imgEl = document.getElementById('q8CurrentImage');
      const fallbackEl = document.getElementById('q8CurrentFallback');

      if (nameEl) nameEl.textContent = item.name;

      if (imgEl) {
        showQ8Loading();
        imgEl.onload = function() {
          hideQ8Loading();
        };
        imgEl.onerror = function() {
          hideQ8Loading();
          if (fallbackEl) {
            fallbackEl.textContent = item.name;
            fallbackEl.style.display = 'flex';
          }
          imgEl.style.display = 'none';
        };
        imgEl.style.display = 'block';
        imgEl.src = '/storage/' + item.file;
        imgEl.alt = item.name;
      }

      if (fallbackEl) {
        fallbackEl.style.display = 'none';
      }

      addQ8ShownItem(item);
      updateQ8Progress();
      updateQ8FullscreenButtons();
    }

    function addQ8ShownItem(item, forceRender = false) {
      if (q8Shown.has(item.file) && !forceRender) return;
      q8Shown.add(item.file);
      const list = document.getElementById('q8ShownList');
      if (!list) return;

      const card = document.createElement('div');
      card.className = 'q8-shown-card';

      const name = document.createElement('div');
      name.className = 'q8-shown-name';
      name.textContent = item.name;

      const img = document.createElement('img');
      img.alt = item.name;
      img.onerror = function() {
        img.remove();
        const fallback = document.createElement('div');
        fallback.className = 'q8-placeholder';
        fallback.textContent = item.name;
        card.insertBefore(fallback, name);
      };
      img.src = '/storage/' + item.file;

      card.appendChild(img);
      card.appendChild(name);
      list.appendChild(card);
    }

    function resetQ8Sequence() {
      ensureQ8Order();
      q8Index = 0;
      q8Shown = new Set();
      const list = document.getElementById('q8ShownList');
      if (list) list.innerHTML = '';

      const step1 = document.getElementById('question8Step1');
      const step2 = document.getElementById('question8Step2');
      if (step1) step1.style.display = 'block';
      if (step2) step2.style.display = 'none';

      updateQ8RestartButton();
      exitQ8Fullscreen();
      renderQ8Current();
      updateQ8NextButton();
      saveState();
    }

    function restartQ8SequenceOnce() {
      if (q8RetryUsed) return;
      q8RetryUsed = true;
      resetQ8Sequence();
      saveState();
    }

    function showQ8AnswerStep() {
      const step1 = document.getElementById('question8Step1');
      const step2 = document.getElementById('question8Step2');
      if (step1) step1.style.display = 'none';
      if (step2) step2.style.display = 'block';
      saveState();
    }

    function showNextQ8Item() {
      if (q8Index < q8Items.length - 1) {
        q8Index += 1;
        renderQ8Current();
        updateQ8NextButton();
        saveState();
        return;
      }
      showQ8AnswerStep();
    }

    function submitQuestion8() {
      const count = parseInt(document.getElementById('itemCountSelect').value, 10);
      const safeCount = isNaN(count) ? 0 : Math.max(0, Math.min(5, count));
      score += safeCount;

      $('#modalQuestion8').modal('hide');
      $('#modalQuestion9').modal('show');
      saveState();
    }

    // ==============================
    // submitQuestion9: 設問9の処理
    // ==============================
    function submitQuestion9() {
      const vegetableScore = parseInt(document.getElementById('vegetableSelect').value, 10);
      score += vegetableScore;
      showResult(); // 結果表示
      saveState();
    }

    document.addEventListener('click', function(event) {
      const actionButton = event.target.closest('[data-action]');
      if (!actionButton) return;
      const action = actionButton.getAttribute('data-action');
      if (!action) return;

      switch (action) {
        case 'calculate-age':
          calculateAge();
          break;
        case 'mark-birth-unknown':
          markBirthUnknown();
          break;
        case 'close-birth-unknown': {
          const flash = document.getElementById('birthUnknownFlash');
          if (flash) flash.style.display = 'none';
          break;
        }
        case 'start-quiz':
          startQuiz();
          break;
        case 'answer': {
          const scoreValue = parseInt(actionButton.getAttribute('data-score'), 10);
          const nextValue = parseInt(actionButton.getAttribute('data-next'), 10);
          if (!isNaN(scoreValue) && !isNaN(nextValue)) {
            handleAnswer(scoreValue, nextValue);
          }
          break;
        }
        case 'submit-q2':
          submitQuestion2();
          break;
        case 'submit-q7':
          submitQuestion7();
          break;
        case 'q8-next':
          showNextQ8Item();
          break;
        case 'q8-restart':
          restartQ8SequenceOnce();
          break;
        case 'q8-fullscreen':
          enterQ8Fullscreen();
          break;
        case 'q8-exit-fullscreen':
          exitQ8Fullscreen();
          break;
        case 'q8-submit':
          submitQuestion8();
          break;
        case 'submit-q9':
          submitQuestion9();
          break;
        case 'return-top':
          returnToTop();
          break;
        case 'reset-quiz':
          resetQuiz();
          break;
        case 'resume-modal':
          if (lastModalId) {
            hideBackNotice();
            $('#' + lastModalId).modal('show');
          }
          break;
        case 'exit-app':
          exitApp();
          break;
        case 'toggle-consult':
          if (actionButton.id === 'consultFab' && actionButton.dataset.dragging === 'true') {
            actionButton.dataset.dragging = 'false';
            break;
          }
          setConsultOpen(!document.body.classList.contains('consult-open'));
          break;
        case 'close-consult':
          setConsultOpen(false);
          break;
        default:
          break;
      }
    });

    document.addEventListener('change', function(event) {
      if (event.target && event.target.matches('input, select, textarea')) {
        saveState();
        if (event.target.id === 'precheckConfirm') {
          updateStartButtonState();
        }
      }
    });

    document.addEventListener('input', function(event) {
      if (event.target && event.target.matches('input[type="text"], textarea')) {
        saveState();
      }
    });

    document.addEventListener('visibilitychange', function() {
      if (document.visibilityState === 'hidden') {
        saveState();
      }
    });

    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        setConsultOpen(false);
      }
    });

    window.addEventListener('pagehide', saveState);

    document.addEventListener('fullscreenchange', updateQ8FullscreenButtons);

    // モーダル表示/非表示で設問8の進行を管理
    $(document).ready(function() {
      const returnTopLink = document.getElementById('returnTopLink');
      if (returnTopLink) {
        returnTopLink.addEventListener('click', () => {
          clearStoredState();
          hideBackNotice();
        });
      }

      $('#modalQuestion3').on('shown.bs.modal', function() {
        scheduleQ3Hint();
      });

      $('#modalQuestion3').on('hidden.bs.modal', function() {
        clearQ3Hint();
      });

      $('#modalQuestion4').on('shown.bs.modal', function() {
        updateQ4Display();
      });

      $('#modalQuestion7').on('shown.bs.modal', function() {
        updateQ7Display();
        if (!hasRestoredState) {
          resetQ7Scores();
        }
      });

      $('#modalQuestion8').on('shown.bs.modal', function() {
        if (!hasRestoredState) {
          resetQ8Sequence();
        } else {
          updateQ8RestartButton();
          updateQ8NextButton();
          updateQ8Progress();
        }
        saveState();
      });

      $('#modalQuestion8').on('hidden.bs.modal', function() {
        if (!hasRestoredState) {
          resetQ8Sequence();
        }
        saveState();
      });

      // 設問2モーダルが表示されたら、検査者用に正しい日付を表示する、検査者用に正しい日付を表示する
      $('#modalQuestion2').on('shown.bs.modal', function() {
        const now = new Date();
        const yyyy = now.getFullYear();
        const mm = now.getMonth() + 1;
        const dd = now.getDate();
        const weekdays = ['日', '月', '火', '水', '木', '金', '土'];
        const wd = weekdays[now.getDay()];
        const display = `<div class="alert alert-light"><strong>正答:</strong> ${yyyy}年 ${mm}月 ${dd}日 （${wd}）</div>`;
        const el = document.getElementById('q2CorrectDate');
        if (el) el.innerHTML = display;

        // (任意) 検査者の手間を減らすため、正解の option を選択状態にしておく
        const yearSelect = document.getElementById('yearSelect');
        const monthSelect = document.getElementById('monthSelect');
        const daySelect = document.getElementById('daySelect');
        const weekdaySelect = document.getElementById('weekdaySelect');
        const hasOldValues = Boolean(
          (yearSelect && yearSelect.dataset.old) ||
          (monthSelect && monthSelect.dataset.old) ||
          (daySelect && daySelect.dataset.old) ||
          (weekdaySelect && weekdaySelect.dataset.old)
        );

        if (!hasRestoredState && !hasOldValues) {
          try {
            if (yearSelect) yearSelect.value = '1';
            if (monthSelect) monthSelect.value = '1';
            if (daySelect) daySelect.value = '1';
            if (weekdaySelect) weekdaySelect.value = '1';
          } catch (e) {
            // noop
          }
        }

        if (birthUnknown) {
          renderQ1SkipNotice();
        } else {
          const notice = document.getElementById('q1SkipNotice');
          if (notice) notice.innerHTML = '';
        }
      });

      $(document).on('shown.bs.modal', '.modal', function() {
        const modalId = this.id;
        if (!modalId) return;
        activeModalId = modalId;
        hideBackNotice();
        history.pushState({ modalId }, '', window.location.href);
        saveState();
      });

      $(document).on('hidden.bs.modal', '.modal', function() {
        const modalId = this.id;
        if (!modalId) return;
        if (isPopStateClose) {
          isPopStateClose = false;
          return;
        }
        const openModal = document.querySelector('.modal.show');
        if (!openModal) {
          activeModalId = null;
          saveState();
        }
        if (history.state && history.state.modalId === modalId) {
          isPopStateClose = true;
          history.back();
        }
      });

      window.addEventListener('popstate', function() {
        const openModal = document.querySelector('.modal.show');
        if (!openModal) return;
        lastModalId = openModal.id || null;
        isPopStateClose = true;
        $(openModal).modal('hide');
        showBackNotice();
      });
    });

    // ==============================
    // showResult: 最終結果
    // ==============================
    function showResult() {
      const renderList = (items) => `
        <ul>
          ${items.map((item) => `<li>${item}</li>`).join('')}
        </ul>
      `;

      const renderBlock = (title, body) => `
        <div class="result-block">
          <div class="result-block-title">${title}</div>
          ${body}
        </div>
      `;

      let heading = '';
      let blocks = [];

      if (score >= 20) {
        heading = '20-30点：異常なし（目安）';
        blocks = [
          renderBlock(
            '結果',
            '<p>現時点では大きな心配は少ないようです（本チェックの目安）</p><p>このチェックは診断ではありませんが、今のところ「強い困りごと」が疑われる結果ではありません。</p>'
          ),
          renderBlock(
            'こんな時は“点数に関わらず”相談を検討してください',
            renderList([
              '物忘れが急に増えた、以前より明らかに生活が回らない',
              '火の消し忘れ、支払いのミス、道に迷うなど「危ない」と感じることがあった',
              '本人（または家族）の不安が強い'
            ])
          ),
          renderBlock(
            '次にやること（おすすめ）',
            renderList([
              '変化に気づきやすくするため、少なくとも3か月に1回程度、本アプリでの定期チェックを続けましょう',
              '最近の気になる出来事があれば、短くメモしておくと安心です（例：いつ・どこで・何があった）'
            ])
          ),
          renderBlock(
            'トップページの案内',
            '<p>不安がある場合は、トップページの「医療機関に相談」「地域包括に相談」などのボタンから、相談方法を確認できます。</p>'
          )
        ];
      } else if (score >= 16) {
        heading = '16-19点：認知症の疑いあり（目安）';
        blocks = [
          renderBlock(
            '結果',
            '<p>認知症の疑いがある可能性があります（本チェックの目安）</p><p>生活の中で“気になる変化”が出ている可能性があります。診断ではありませんが、早めに相談するほど安心につながります。</p>'
          ),
          renderBlock(
            'この点数帯で起こりやすい困りごと（例）',
            renderList([
              '予定や約束を忘れる／同じことを何度も確認する',
              '物の置き場所が分からなくなる回数が増える',
              '支払い・料理・片付けなど、手順が多いことが面倒に感じる'
            ])
          ),
          renderBlock(
            '今すぐできること（負担を増やさないコツ）',
            renderList([
              '「探し物を減らす」ため、置き場所を固定する（鍵・財布・薬など）',
              'メモやカレンダーを“見える場所1か所”にまとめる',
              '1人で抱えず、家族や身近な人に「最近こんなことがある」と共有する'
            ])
          ),
          renderBlock(
            '次にやること（大切）',
            renderList([
              '点数が気になる場合は、トップページの「医療機関に相談」から相談の準備を進めましょう',
              '受診までの間に状態の変化を見たい場合は、1-2か月後に当アプリで再チェックしてもOKです（※再チェックで安心しきるのではなく、困りごとが続くなら相談を優先してください）'
            ])
          )
        ];
      } else if (score >= 11) {
        heading = '11-15点：中程度の認知症（可能性）';
        blocks = [
          renderBlock(
            '結果',
            '<p>中程度の認知症が疑われる可能性があります（本チェックの目安）</p><p>日常生活に影響が出ている可能性があります。診断ではありませんが、早めの受診・支援につなぐ準備をおすすめします。</p>'
          ),
          renderBlock(
            'この点数帯で起こりやすい困りごと（例）',
            renderList([
              '支払い・手続き・家計管理が難しくなる',
              '薬を飲み忘れる、飲んだか分からなくなる',
              '料理や片付けなどの段取りが続かない',
              '外出や人付き合いが不安になり、避けがちになる'
            ])
          ),
          renderBlock(
            '今すぐできること（安全と負担軽減）',
            renderList([
              '薬・鍵・財布などは“置き場所固定＋見える化”',
              '「やること」を紙に1つずつ書く（複数同時に抱えない）',
              '重要な判断（高額な買い物・契約など）は、1人で決めない'
            ])
          ),
          renderBlock(
            '次にやること（最優先）',
            renderList([
              'トップページの「医療機関に相談」「地域包括に相談」から、早めに相談の準備を進めてください',
              'この点数帯は「様子見の再チェック」より相談・受診が優先です（再チェックは、相談後の経過確認として行うのがおすすめ）'
            ])
          )
        ];
      } else if (score >= 5) {
        heading = '5-10点：やや高度の認知症（可能性）';
        blocks = [
          renderBlock(
            '結果',
            '<p>やや高度の認知症が疑われる可能性があります（本チェックの目安）</p><p>安全面への配慮が必要になる可能性があります。診断ではありませんが、早急に受診・相談し、生活支援の体制を整えることをおすすめします。</p>'
          ),
          renderBlock(
            'この点数帯で起こりやすい困りごと（例）',
            renderList([
              '火の扱い、戸締まり、服薬、金銭などでミスが増える',
              '外出先で不安が強くなる／道に迷う',
              '不安・怒りっぽさ・落ち着かなさが出ることがある（体調や環境で変わります）'
            ])
          ),
          renderBlock(
            '今すぐできること（危険を減らす）',
            renderList([
              '火元・戸締まり・薬・財布など事故につながりやすい所を優先して見直す',
              '1人で外出しない／連絡手段を持つなど、安全策を増やす',
              'できるだけ早く、家族や身近な人に「今日の結果」を共有する'
            ])
          ),
          renderBlock(
            '次にやること（急ぎ）',
            renderList([
              'トップページの「医療機関に相談」「地域包括に相談」を優先して進めてください',
              '運転をしている場合は、トップページの「運転の相談」も確認してください',
              'この点数帯は再チェックより相談が優先です'
            ])
          )
        ];
      } else {
        heading = '0-4点：高度の認知症（可能性）';
        blocks = [
          renderBlock(
            '結果',
            '<p>高度の認知症が疑われる可能性があります（本チェックの目安）</p><p>安全確保と支援体制が最優先です。診断ではありませんが、できるだけ早く医療機関へ相談し、必要な支援につなげてください。</p>'
          ),
          renderBlock(
            'この点数帯で起こりやすい困りごと（例）',
            renderList([
              '日常生活の多くで、見守りや手助けが必要になる',
              '体調の変化で混乱が強くなることがある',
              '外出・服薬・火・金銭などで事故やトラブルの心配が増える'
            ])
          ),
          renderBlock(
            '今すぐできること（最優先）',
            renderList([
              '1人で抱えず、家族や身近な人にすぐ共有する',
              '危険につながりやすい行動（火、運転、単独外出等）を控える',
              'トップページの「医療機関に相談」「地域包括に相談」から、すぐに相談の準備を進める'
            ])
          ),
          renderBlock(
            '次にやること',
            renderList([
              'この点数帯は再チェックではなく相談・支援導入が最優先です'
            ])
          )
        ];
      }

      const resultTextEl = document.getElementById('resultText');
      if (resultTextEl) {
        resultTextEl.innerHTML = `
          <div class="result-heading">${heading}</div>
          ${blocks.join('')}
        `;
      }

      const audienceEl = document.getElementById('resultAudience');
      if (audienceEl) {
        audienceEl.innerHTML = `
          <div class="result-footer-text">
            ※本チェックは医学的な診断ではありません。認知症の診断を下せるのは医師のみです。<br />
            体調（睡眠不足・疲労・ストレス）や薬、環境の変化などで結果が変動することがあります。<br />
            不安がある場合は、トップページの各ボタンから相談方法を確認してください。
          </div>
        `;
      }

      const scoreDisplay = document.getElementById('scoreDisplay');
      if (scoreDisplay) {
        scoreDisplay.innerText = `合計スコア: ${score} 点`;
      }

      $('#modalResult').modal('show');
    }

    // ==============================
    // resetQuiz: 検査を最初からやり直す
    // ==============================
    function resetQuiz() {
      // すべてのモーダルを閉じる
      $('.modal').modal('hide');
      resetQ8Sequence();

      // 状態リセット
      score = 0;
      correctAge = null;
      birthUnknown = false;
      q8Initialized = false;
      q8RetryUsed = false;
      resetQ7Scores();

      // UI を初期状態に戻す
      document.getElementById('introSection').style.display = 'block';
      const flash = document.getElementById('birthUnknownFlash');
      if (flash) {
        flash.style.display = 'none';
        flash.innerHTML = '';
      }
      const startBtn = document.getElementById('startQuizBtn');
      const confirm = document.getElementById('precheckConfirm');
      if (confirm) confirm.checked = false;
      if (startBtn) updateStartButtonState();

      const calcArea = document.getElementById('calculatedAgeArea');
      if (calcArea) calcArea.style.display = 'none';

      const q1Notice = document.getElementById('q1SkipNotice');
      if (q1Notice) q1Notice.innerHTML = '';

      q4SelectedSet = 'setA';
      const defaultWord = document.querySelector('input[name="q4WordSet"][value="setA"]');
      if (defaultWord) defaultWord.checked = true;
      updateQ4Display();

      // リセット後、年のプルダウンは残すが選択を最新に
      try {
        populateYears(1900);
      } catch (e) {
        // noop
      }

      clearStoredState();
      hasRestoredState = false;
      activeModalId = null;
      lastModalId = null;
      hideBackNotice();
    }

    function returnToTop() {
      resetQuiz();
      window.location.replace(window.location.origin + '/');
    }
