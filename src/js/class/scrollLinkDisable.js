/**
 * 【スクロール中はリンク無効化】
 *
 * 使用例：
 * const SLD = new ScrollLinkDisable();
 *
 */

export default class LinkDisable {
  constructor() {
    this.init();
  }

  init() {
    const _body = document.querySelector('body');
    let timer = null;

    const disableHover = () => {
      _body.classList.add('disable-hover');
      clearTimeout(timer);
      timer = setTimeout(() => {
        _body.classList.remove('disable-hover');
      }, 300);
    };

    let scheduled = false;
    window.addEventListener(
      'scroll',
      () => {
        if (!scheduled) {
          requestAnimationFrame(() => {
            disableHover();
            scheduled = false;
          });
          scheduled = true;
        }
      },
      { passive: true }
    );
  }
}
