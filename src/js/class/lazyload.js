/**
 * 【画像の遅延読み込み】
 *
 * js:
 * const LZY = new LazyLoad('20% 10% 20% 10%');
 * ※引数はrootMarginの値。
 *
 * html:
 * <img data-lzy data-src="画像のパス" alt="">
 */

export default class LazyLoad {
  constructor(rootMargin = '0px') {
    this.rootMargin = rootMargin;
    this.lazyImageObserver = null;
    this.initObserver();
  }

  update(element) {
    // ここで新しく追加されたelementのみを監視対象にするロジックを追加
    if (element && this.lazyImageObserver) {
      this.lazyImageObserver.observe(element);
    }
  }

  initObserver() {
    if ('IntersectionObserver' in window && !this.lazyImageObserver) {
      const options = {
        rootMargin: this.rootMargin,
      };

      this.lazyImageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            let lazyImage = entry.target;

            if (lazyImage.dataset.src !== undefined) {
              lazyImage.src = lazyImage.dataset.src;
            }

            if (lazyImage.dataset.srcset !== undefined) {
              lazyImage.srcset = lazyImage.dataset.srcset;
            }

            lazyImage.onload = () => lazyImage.classList.add('action');
            observer.unobserve(lazyImage);
          }
        });
      }, options);

      document.querySelectorAll('[data-lzy]').forEach((lazyImage) => this.lazyImageObserver.observe(lazyImage));
    }
  }
}
