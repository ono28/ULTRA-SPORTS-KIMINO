import { Intersection } from "../vendor/splidejs-splide-extension-intersection.min.js";
import { AutoScroll } from "../vendor/splidejs-splide-extension-auto-scroll.min.js";
import { Splide } from "../vendor/splidejs-splide.min.js";

import ScrollClass from './class/scrollClass.js';
import { utils } from './utils/util.js';

// ----------------------------------------------------------
// DOM要素
// ----------------------------------------------------------
const _html = document.documentElement;
const _body = document.body;
const _loading = document.getElementById('loading');
const _gHeader = document.getElementById('globalHeader');

// ----------------------------------------------------------
// グローバルAPI
// ----------------------------------------------------------
const APP = (window.APP ||= {});

// ----------------------------------------------------------
// 設定
// ----------------------------------------------------------

// ----------------------------------------------------------
// ページ内関数
// ----------------------------------------------------------
// グロナビ カレント表示
function setCurrentNavi() {
  const currentPage = _body.dataset.page;
  if (currentPage) {
    document.querySelectorAll('.nav').forEach((el) => {
      el.classList.toggle('active', el.classList.contains(`n_${currentPage}`));
    });
  }
}

// アコーディオン
function setAccordion() {
  const _acc = document.querySelectorAll('.acc');
  if (_acc.length == 0) return;

  _acc.forEach((el) => {
    const _accTrigger = el.querySelector('.acc_trigger');
    const _accBody = el.querySelector('.acc_body');

    utils.slideUp(_accBody, 0);

    _accTrigger.addEventListener('click', () => {
      utils.slideToggle(_accBody, 300);
    });
  });
}

// splide
function setSplide() {
  const _splide = document.querySelectorAll('.splide');
  if (_splide.length == 0) return;

  _splide.forEach((el) => {
    const slide = new Splide(el, {
      type: 'loop',
      arrows: false,
      pagination: false,
      drag: false,
      autoWidth: true,
      autoScroll: {
        speed: 1,
        pauseOnHover: false,
      },
      intersection: {
        inView: {
          autoScroll: true,
        },
        outView: {
          autoScroll: false,
        },
      },
      breakpoints: {
        760: {
          autoScroll: {
            speed: 0.7,
          },
        },
      },
    });

    slide.on('mounted', () => {
      el.querySelectorAll('[data-lzy]').forEach((el) => {
        APP.LZY.update(el);
      });
    });
    slide.mount({ AutoScroll, Intersection });
  });
}

// header
function setHeader() {
  window.addEventListener('scroll', () => {
    if (window.scrollY > 100) {
      _gHeader.classList.add('white');
    } else {
      _gHeader.classList.remove('white');
    }
  });
}

// ----------------------------------------------------------
// 初期化
// ----------------------------------------------------------
async function quickSettings() {
  utils.setFillHeight();
  utils.getScrollbarWidth();

  // 関数初期化
  setCurrentNavi();
  setAccordion();
  setSplide();
  setHeader();

  await utils.delay(200);

  // ローディング
  _loading.classList.add('hide');
  _html.classList.remove('loading');

  await utils.delay(300);

  // ScrollClass初期化
  const scrollTargets = document.querySelectorAll('[data-target]');
  if (scrollTargets.length > 0) {
    APP.SC = new ScrollClass(scrollTargets, {
      rootMargin: '-10% 0px',
      delay: 100,
    });
  }
}

// ----------------------------------------------------------
// 実行
// ----------------------------------------------------------
_html.classList.add('loading');

window.addEventListener('DOMContentLoaded', () => {
  quickSettings();

  // アンカーリンクがある場合、#globalHeaderの高さ分オフセットしてスクロール
  if (window.location.hash) {
    const id = window.location.hash.substring(1);
    const target = document.getElementById(id);

    if (target && _gHeader) {
      // ページ描画後にスクロール（遅延実行）
      setTimeout(() => {
        const headerHeight = _gHeader.offsetHeight;
        const rect = target.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const top = rect.top + scrollTop - headerHeight;
        window.scrollTo({ top, behavior: 'auto' });
      }, 10);
    }
  }
});
