export const utils = {
  // usage
  // var element = utils.eq('.list > .item', 2);
  // => <div class="item">3</div>
  eq: (selector, index) => {
    var nodeList = document.querySelectorAll(selector),
      length = nodeList.length;

    if (0 <= index) {
      return nodeList[index];
    }

    return null;
  },

  // usage
  // var index = utils.index(document.querySelectorAll('.hoge'), document.querySelector('target'));
  // => 4
  index: (selector, target) => {
    var nodeList = selector,
      element = target;

    // 第2引数を省略したとき
    if (typeof target === 'undefined') {
      return Array.prototype.indexOf.call(nodeList[0].parentNode.children, nodeList[0]);
    }

    return Array.prototype.indexOf.call(nodeList, element);
  },

  slideUp: function (target, duration = 500) {
    target.style.transitionProperty = 'height, margin, padding';
    target.style.transitionDuration = duration + 'ms';
    target.style.boxSizing = 'border-box';
    target.style.height = target.offsetHeight + 'px';
    target.offsetHeight;
    target.style.overflow = 'hidden';
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;

    window.setTimeout(() => {
      target.style.display = 'none';
      target.style.removeProperty('height');
      target.style.removeProperty('padding-top');
      target.style.removeProperty('padding-bottom');
      target.style.removeProperty('margin-top');
      target.style.removeProperty('margin-bottom');
      target.style.removeProperty('overflow');
      target.style.removeProperty('transition-duration');
      target.style.removeProperty('transition-property');
    }, duration);
  },

  slideDown: function (target, duration = 500) {
    target.style.removeProperty('display');
    let display = window.getComputedStyle(target).display;

    if (display === 'none') {
      display = 'block';
    }

    target.style.display = display;
    let height = target.offsetHeight;
    target.style.overflow = 'hidden';
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    target.offsetHeight;
    target.style.boxSizing = 'border-box';
    target.style.transitionProperty = 'height, margin, padding';
    target.style.transitionDuration = duration + 'ms';
    target.style.height = height + 'px';
    target.style.removeProperty('padding-top');
    target.style.removeProperty('padding-bottom');
    target.style.removeProperty('margin-top');
    target.style.removeProperty('margin-bottom');

    window.setTimeout(() => {
      target.style.display = display;
      target.style.removeProperty('height');
      target.style.removeProperty('overflow');
      target.style.removeProperty('transition-duration');
      target.style.removeProperty('transition-property');
    }, duration);
  },

  slideToggle: function (target) {
    target.parentElement.classList.toggle('active');

    if (window.getComputedStyle(target).display === 'none') {
      return this.slideDown(target);
    } else {
      return this.slideUp(target);
    }
  },

  // usage
  // long_press(ターゲットDOM, 通常時の関数, 長押し時の関数, 長押し判定秒数(ms));
  long_press: (el, nf, lf, sec) => {
    let longclick = false;
    let longtap = false;
    let touch = false;
    let timer;

    el.addEventListener('touchstart', () => {
      touch = true;
      longtap = false;
      timer = setTimeout(() => {
        longtap = true;
        lf();
      }, sec);
    });

    el.addEventListener('touchend', () => {
      if (!longtap) {
        clearTimeout(timer);
        nf();
      } else {
        touch = false;
      }
    });

    el.addEventListener('mousedown', () => {
      if (touch) return;
      longclick = false;
      timer = setTimeout(() => {
        longclick = true;
        lf();
      }, sec);
    });

    el.addEventListener('click', () => {
      if (touch) {
        touch = false;
        return;
      }
      if (!longclick) {
        clearTimeout(timer);
        nf();
      }
    });
  },

  // marginを含めたwidth heightを返す
  getOuterSize: (element) => {
    const getBoundingSize = (elm) => {
      const { width, height } = elm.getBoundingClientRect();
      return { width, height };
    };

    const getElementMargin = (elm) => {
      const styles = window.getComputedStyle(elm);
      return ['top', 'right', 'bottom', 'left'].reduce((obj, key) => {
        return {
          ...obj,
          [key]: parseFloat(styles.getPropertyValue(`margin-${key}`)) || 0,
        };
      });
    };

    let { top, right, bottom, left } = getElementMargin(element);
    top = top == undefined ? 0 : top;
    bottom = bottom == undefined ? 0 : bottom;
    right = right == undefined ? 0 : right;
    left = left == undefined ? 0 : left;

    const { width, height } = getBoundingSize(element);
    return {
      width: width + right + left,
      height: height + top + bottom,
    };
  },

  // スマホ100vh問題用
  setFillHeight: () => {
    let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);

    let dvh = document.documentElement.clientHeight * 0.01;
    document.documentElement.style.setProperty('--dvh', `${dvh}px`);

    window.addEventListener('resize', () => {
      vh = window.innerHeight * 0.01;
      document.documentElement.style.setProperty('--vh', `${vh}px`);

      dvh = document.documentElement.clientHeight * 0.01;
      document.documentElement.style.setProperty('--dvh', `${dvh}px`);
    });
  },

  // タッチデバイス判定
  isTouch: () => {
    const touch_event = window.ontouchstart;
    const touch_points = navigator.maxTouchPoints;
    return touch_event !== undefined && 0 < touch_points;
  },

  addAction: (target, index) => {
    const delay = 100 * (index + 1);

    target.style.transitionDelay = `${delay}ms`;
    target.classList.add('action');
    target.addEventListener('transitionend', () => {
      target.style.transitionDelay = ``;
    });
  },

  removeAction: (target) => {
    target.classList.remove('action');
    target.style.transitionDelay = ``;
  },

  isMobile: () => {
    return /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
  },

  getMobileOS: () => {
    const ua = navigator.userAgent;

    if (/android/i.test(ua)) {
      document.querySelector('body').classList.add('is_android');
      return 'Android';
    } else if (navigator.userAgent.indexOf('iPhone') > 0 || navigator.userAgent.indexOf('iPod') > 0) {
      document.querySelector('body').classList.add('is_iphone');
      return 'iOS';
    } else if (/iPad/.test(ua) || (navigator.userAgent.indexOf('Safari') > 0 && navigator.userAgent.indexOf('Chrome') == -1 && typeof document.ontouchstart !== 'undefined')) {
      document.querySelector('body').classList.add('is_ipad');
      return 'iOS';
    }

    return 'Other';
  },

  getAngle: () => {
    // 角度を取得
    let angle = screen && screen.orientation && screen.orientation.angle;
    if (angle === undefined) {
      angle = window.orientation; // iOS用
    }

    const isPortrait = angle === 0;
    return {
      value: angle, // 具体的な角度
      isPortrait: isPortrait, // 縦向き
      isLandscape: !isPortrait, // 横向き
    };
  },

  isLocalhost(url) {
    const pattern = new RegExp('^(https?:\\/\\/)?' + '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + '((\\d{1,3}\\.){3}\\d{1,3}))' + '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + '(\\?[;&a-z\\d%_.~+=-]*)?' + '(\\#[-a-z\\d_]*)?$', 'i');

    if (!pattern.test(url)) return false; // URLであるかのCHECK

    let parser = document.createElement('a');

    parser.href = url;

    return parser.hostname === 'localhost' || parser.hostname === '127.0.0.1';
  },

  getWeek(_tgtDay) {
    const WeekChars = ['日', '月', '火', '水', '木', '金', '土'];
    let tgt = _tgtDay.split('-');
    let d = new Date(tgt[0], tgt[1] - 1, tgt[2]);

    return WeekChars[d.getDay()];
  },

  // 一時的な要素を作成してスクロールバーの幅を計算
  getScrollbarWidth: () => {
    const outer = document.createElement('div');
    outer.style.visibility = 'hidden';
    outer.style.overflow = 'scroll';
    document.body.appendChild(outer);

    const inner = document.createElement('div');
    outer.appendChild(inner);

    const scrollbarWidth = outer.offsetWidth - inner.offsetWidth || 0;

    // 作成した要素を削除
    outer.parentNode.removeChild(outer);

    document.documentElement.style.setProperty('--scrollbarWidth', `${scrollbarWidth}px`);

    return scrollbarWidth;
  },

  // Promise ベースの遅延処理
  // ex) await utils.delay(1000)
  delay(ms) {
    return new Promise((resolve) => {
      const startTime = performance.now();
      function step(currentTime) {
        if (currentTime - startTime >= ms) {
          resolve();
        } else {
          requestAnimationFrame(step);
        }
      }
      requestAnimationFrame(step);
    });
  },

  // ジャンプアンカーリンク
  // ex) utils.smoothNear(document.querySelector(id), オフセット数値, 'easeOutQuart');
  smoothNear(_targetEl, offset = 0, easingType = 'easeOutCubic', durationTime = 400) {
    const easing = {
      linear: (t) => t,

      // Ease In
      easeInQuad: (t) => t * t,
      easeInCubic: (t) => t * t * t,
      easeInQuart: (t) => t ** 4,
      easeInQuint: (t) => t ** 5,

      // Ease Out
      easeOutQuad: (t) => 1 - (1 - t) ** 2,
      easeOutCubic: (t) => 1 - (1 - t) ** 3,
      easeOutQuart: (t) => 1 - (1 - t) ** 4,
      easeOutQuint: (t) => 1 - (1 - t) ** 5,

      // Ease In Out
      easeInOutQuad: (t) => (t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2),
      easeInOutCubic: (t) => (t < 0.5 ? 4 * t ** 3 : 1 - Math.pow(-2 * t + 2, 3) / 2),
      easeInOutQuart: (t) => (t < 0.5 ? 8 * t ** 4 : 1 - Math.pow(-2 * t + 2, 4) / 2),
      easeInOutQuint: (t) => (t < 0.5 ? 16 * t ** 5 : 1 - Math.pow(-2 * t + 2, 5) / 2),
    };

    // ページの最大スクロール位置を取得
    const maxScrollY = document.documentElement.scrollHeight - window.innerHeight;

    let targetPos = _targetEl.getBoundingClientRect().top + window.pageYOffset - offset;
    if (targetPos < 0) targetPos = 0;
    if (targetPos > maxScrollY) targetPos = maxScrollY;

    const current = window.pageYOffset;
    const distance = targetPos - current;

    // 最後の100pxだけをアニメーション（ただし実際の移動距離以下に制限）
    const threshold = Math.min(100, Math.abs(distance));

    // threshold以上離れてたら、まずは一瞬で移動（差分 -threshold の位置へ）
    if (Math.abs(distance) > threshold) {
      const jumpPos = targetPos - Math.sign(distance) * threshold;
      window.scrollTo(0, jumpPos);
    }

    // ここから最後のthresholdピクセルだけスムーズにする
    const start = window.pageYOffset;
    const end = targetPos;
    const duration = durationTime;
    const startTime = performance.now();

    const animate = (now) => {
      const t = Math.min(1, (now - startTime) / duration);
      const ease = easing[easingType](t);
      const newY = start + (end - start) * ease;
      window.scrollTo(0, newY);

      if (t < 1) {
        requestAnimationFrame(animate);
      }
    };

    requestAnimationFrame(animate);
  },
};
