/**
 * 【モーダル】
 *
 * js:
 * const MODAL = new Modal();
 *
 * 注意:
 * - 開く・閉じるは外部操作
 *
 */

export default class ModalHandler {
  constructor(selector = '#modal') {
    this._body = document.body;
    this._modal = document.querySelector(selector);
    this._scrollY = 0;
    this.isOpen = false;

    if (!this._modal) throw new Error('modal not found');
  }

  _delay(ms) {
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
  }

  _removeAction(target) {
    target.classList.remove('action');
    target.style.transitionDelay = ``;
  }

  _lockScroll() {
    this._scrollY = window.scrollY;
    this._body.classList.add('noscroll');
    this._body.style.top = `-${this._scrollY}px`;
  }

  _unlockScroll() {
    this._body.classList.remove('noscroll');
    this._body.style.top = '';
    window.scrollTo(0, this._scrollY);
  }

  _activateBody(id) {
    this._modal.querySelectorAll('.modal_body').forEach((el) => el.classList.toggle('active', el.id === id));
  }

  _deactivateBodies() {
    this._modal.querySelectorAll('.modal_body').forEach((el) => el.classList.remove('active'));
  }

  open(id) {
    if (this.isOpen) return;
    this.isOpen = true;

    this._activateBody(id);
    this._lockScroll();
    this._modal.classList.add('active');
  }

  async close() {
    if (!this.isOpen) return;
    this.isOpen = false;

    this._unlockScroll();
    this._modal.classList.remove('active');
    this._deactivateBodies();

    this._body.style.top = '';
    window.scrollTo(0, this._scrollY);

    await this._delay(300);
    this._modal.querySelectorAll('[data-target]').forEach((el, index) => {
      this._removeAction(el);
    });
  }
}
