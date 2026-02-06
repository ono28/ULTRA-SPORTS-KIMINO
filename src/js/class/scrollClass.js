/**
 * 【画面内に入ったらclass付与】
 *
 * js：
 * // 全てデフォルト値でインスタンスを作成
 * const scrollClassDefault = new ScrollClass(document.querySelectorAll('[data-target]'));
 *
 * // delayのみをカスタマイズしてインスタンスを作成
 * const scrollClassWithDelay = new ScrollClass(document.querySelectorAll('[data-target].withDelay'), { delay: 500 });
 *
 * // toggleをtrueにして、rootMarginとdelayをカスタマイズ
 * const scrollClassWithToggle = new ScrollClass(document.querySelectorAll('[data-target].withToggle'), { toggle: true, rootMargin: '10%', delay: 300 });
 *
 * オプション：
 * - toggle: 要素がビューポートに入ったときにクラスを追加し、出たときに削除するかどうかのブール値（デフォルトはfalse）
 * - rootMargin: IntersectionObserverのrootMarginプロパティ（デフォルトは'0px'）
 * - delay: クラス追加の遅延時間（ミリ秒）（デフォルトは200）
 *
 */

export default class ScrollClass {
  constructor(element, { toggle = false, rootMargin = '0px', delay = 200 } = {}) {
    this.dom = element;
    this.toggle = toggle;
    this.rootMargin = rootMargin;
    this.delay = delay;
    this.lazyObjectObserver = null;

    this.initObserver();
  }

  update(element) {
    // ここで新しく追加されたelementのみを監視対象にするロジックを追加
    if (element && this.lazyObjectObserver) {
      this.lazyObjectObserver.observe(element);
    }
  }

  initObserver() {
    if ('IntersectionObserver' in window && !this.lazyObjectObserver) {
      const options = {
        rootMargin: this.rootMargin,
      };

      this.lazyObjectObserver = new IntersectionObserver((entries) => {
        let count = 0;
        entries.forEach((entry, i) => {
          const lazyTarget = entry.target;

          if (lazyTarget.dataset.ignore) {
            return;
          }

          if (entry.isIntersecting) {
            // 要素がビューポートに入る
            requestAnimationFrame(() => {
              count++;
              lazyTarget.style.transitionDelay = `${this.delay * count}ms`;
              lazyTarget.classList.add('action');
            });

            const onEnd = () => {
              lazyTarget.style.transitionDelay = '';
              lazyTarget.removeEventListener('transitionend', onEnd);
            };

            lazyTarget.addEventListener('transitionend', onEnd);
          } else if (this.toggle) {
            // toggleがtrueの場合、要素がビューポートから出るとクラスを削除
            requestAnimationFrame(() => {
              lazyTarget.classList.remove('action');
            });
          }
        });
      }, options);

      this.dom.forEach((lazyTarget) => {
        this.lazyObjectObserver.observe(lazyTarget);
      });
    }
  }
}
