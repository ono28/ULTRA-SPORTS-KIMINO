import lottieWeb from 'lottie-web';

// ----------------------------------------------------------
// 設定
// ----------------------------------------------------------
const LOTTIE_PC_JSON = 'wp/wp-content/themes/ultra-sports-kimino/assets/data/mv_pc/data.json';
const LOTTIE_SP_JSON = 'wp/wp-content/themes/ultra-sports-kimino/assets/data/mv_sp/data.json';

// ----------------------------------------------------------
// ページ内関数
// ----------------------------------------------------------
function isSP() {
  // 768px未満をSPと判定
  return window.matchMedia('(max-width: 767px)').matches;
}

function createLottiePlayer(jsonUrl) {
  const lottieDiv = document.getElementById('lottie');
  if (!lottieDiv) return;

  // lottie初期化
  lottieWeb.loadAnimation({
    container: lottieDiv,
    renderer: 'svg',
    loop: false,
    autoplay: false,
    path: jsonUrl,
  });

  // 画面内に入ったら一度だけ再生
  let played = false;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting && !played) {
          played = true;
          lottieWeb.play();
          observer.disconnect();
        }
      });
    },
    {
      threshold: 0.1,
    }
  );

  observer.observe(lottieDiv);
}

// ----------------------------------------------------------
// 初期化
// ----------------------------------------------------------
async function quickSettings() {
  const jsonUrl = isSP() ? LOTTIE_SP_JSON : LOTTIE_PC_JSON;
  createLottiePlayer(jsonUrl);
}

// ----------------------------------------------------------
// 実行
// ----------------------------------------------------------
window.addEventListener('DOMContentLoaded', () => {
  quickSettings();
});
