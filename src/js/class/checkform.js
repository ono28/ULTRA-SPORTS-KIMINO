/**
 * 【入力フォームの状態チェック】
 *
 * requiredに設定された項目の入力状態をチェックして.btn_submitのclassを切り替える
 * メール形式、電話番号形式などもバリデーション
 *
 * js:
 * const CHECKFORM = new CheckForm(フォームのDOM, messages);
 *
 */

let isValid = true;
let isMailValid = true;

export default class CheckForm {
  constructor(element, messages = null) {
    this.dom = element;
    this.messages = messages;
    this.submitButton = this.dom.querySelector('.btn_submit');
    // requiredInputsは動的に取得するため、ここでは取得しない
    this.radioInputs = this.dom.querySelectorAll('input[type="radio"]');
    this.checkboxInputs = this.dom.querySelectorAll('input[type="checkbox"]');

    this.attachEventListeners();
  }

  attachEventListeners() {
    const _this = this;

    // changeイベントに加えてinputイベントも監視（リアルタイム更新）
    this.dom.addEventListener('input', (event) => {
      if (event.target.matches('input, select, textarea')) {
        this.validateField(event.target);
        this.check();
      }
    });

    this.dom.addEventListener('change', (event) => {
      if (event.target.matches('input, select, textarea')) {
        this.validateField(event.target);
        this.check();
      }
    });

    // blurイベントでもバリデーション
    this.dom.addEventListener(
      'blur',
      (event) => {
        if (event.target.matches('input, select, textarea')) {
          this.validateField(event.target);
        }
      },
      true
    );

    this.check(); // 初期チェックを実行
  }

  // 個別フィールドのバリデーション
  validateField(input) {
    if (!this.messages) return;

    const fieldName = input.name;
    const value = input.value.trim();
    const type = input.type;
    const errorEl = this.dom.querySelector(`[data-error="${fieldName}"]`);

    if (!errorEl) return;

    let errorMessage = '';

    // チェックボックスの必須チェック
    if (type === 'checkbox' && input.required && !input.checked) {
      errorMessage = this.messages.validation[fieldName + '_required'] || this.messages.validation.checkbox_required || 'この項目は必須です。';
    }
    // 必須チェック（テキストフィールド等）
    else if (input.required && value === '') {
      errorMessage = this.messages.validation[fieldName + '_required'] || this.messages.validation.input_required || 'この項目は必須です。';
    }
    // メール形式チェック
    else if (type === 'email' && value !== '' && !this.isValidEmail(value)) {
      errorMessage = this.messages.validation.email_invalid;
    }
    // 電話番号形式チェック
    else if (type === 'tel' && value !== '' && !this.isValidTel(value)) {
      errorMessage = this.messages.validation.tel_invalid;
    }
    // URL形式チェック
    else if (type === 'url' && value !== '' && !this.isValidUrl(value)) {
      errorMessage = this.messages.validation.url_invalid;
    }

    // メールアドレス確認用チェック
    if (fieldName === 'email2') {
      const emailInput = this.dom.querySelector('input[name="email"]');
      if (emailInput && value !== emailInput.value.trim()) {
        errorMessage = this.messages.validation.email2_not_match || 'メールアドレスが一致しません。';
      }
    }

    // エラーメッセージ表示
    if (errorMessage) {
      errorEl.textContent = errorMessage;
      errorEl.classList.add('show');
      input.classList.add('error');
    } else {
      errorEl.textContent = '';
      errorEl.classList.remove('show');
      input.classList.remove('error');
    }
  }

  // メールアドレス形式チェック
  isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }

  // 電話番号形式チェック（数字とハイフンのみ）
  isValidTel(tel) {
    const re = /^[0-9\-]+$/;
    return re.test(tel);
  }

  // URL形式チェック
  isValidUrl(url) {
    try {
      new URL(url);
      return true;
    } catch {
      return false;
    }
  }

  check() {
    isValid = true;

    // 必須フィールドを毎回動的に取得（required属性の変更に対応）
    const requiredInputs = this.dom.querySelectorAll('input:required, select:required, textarea:required');

    // 必須フィールドのチェック
    requiredInputs.forEach((input) => {
      // 非表示要素はスキップ
      if (input.offsetParent === null) return;

      const value = input.value.trim();
      const type = input.type;

      // 空値チェック
      if (value === '') {
        isValid = false;
        return;
      }

      // 形式チェック
      if (type === 'email' && !this.isValidEmail(value)) {
        isValid = false;
      } else if (type === 'tel' && !this.isValidTel(value)) {
        isValid = false;
      } else if (type === 'url' && !this.isValidUrl(value)) {
        isValid = false;
      }
    });

    // emailとemail2の一致チェック
    const emailInput = this.dom.querySelector('input[name="email"]');
    const email2Input = this.dom.querySelector('input[name="email2"]');
    if (emailInput && email2Input) {
      if (emailInput.value.trim() !== email2Input.value.trim()) {
        isValid = false;
      }
    }

    // ラジオボタンのチェック（required属性があり、グループで1つも選択されていない場合）
    const radioGroups = new Set();
    this.radioInputs.forEach((input) => {
      if (input.required && input.name) {
        radioGroups.add(input.name);
      }
    });

    radioGroups.forEach((name) => {
      if (!this.dom.querySelector(`input[name="${name}"]:checked`)) {
        isValid = false;
      }
    });

    // チェックボックス（required属性がある場合は個別にチェック）
    this.checkboxInputs.forEach((checkbox) => {
      if (checkbox.required && checkbox.offsetParent !== null) {
        if (!checkbox.checked) {
          isValid = false;
        }
      }
    });

    // 提出ボタンの状態の更新
    if (!isValid || !isMailValid) {
      this.submitButton.classList.add('disable');
      this.submitButton.disabled = true;
    } else {
      this.submitButton.classList.remove('disable');
      this.submitButton.disabled = false;
    }
  }
}
