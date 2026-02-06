import ScrollClass from './class/scrollClass.js';
import { utils } from './utils/util.js';

// ----------------------------------------------------------
// DOM要素
// ----------------------------------------------------------
const _html = document.documentElement;
const _body = document.body;
const _loading = document.getElementById('loading');

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

// MV
async function setMV() {
  const _mv = document.getElementById('mv');
  if (!_mv) return;

  const _mo1 = _mv.querySelector('.mo1');
  const _mo2 = _mv.querySelector('.mo2');
  const _txt = _mv.querySelector('.mv_txt');
  const _news = _mv.querySelector('.mv_news');
  const _scroll = _mv.querySelector('.ic_scroll');

  await utils.delay(400);
  _mo1.classList.add('active');
  _mo2.classList.add('active');

  await utils.delay(800);
  _txt.classList.add('active');

  await utils.delay(800);
  _news.classList.add('active');
  _scroll.classList.add('active');
}

// ----------------------------------------------------------
// 初期化
// ----------------------------------------------------------
async function quickSettings() {
  utils.setFillHeight();
  utils.getScrollbarWidth();

  // 関数初期化
  setCurrentNavi();
  setMV();

  // ローディング
  _loading.classList.add('hide');
  _html.classList.remove('loading');

  await utils.delay(400);

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
});
