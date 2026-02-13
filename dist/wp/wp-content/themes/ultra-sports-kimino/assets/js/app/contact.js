import CheckForm from './class/checkform.js';
import { utils } from './utils/util.js';
import { validationMessages } from './utils/validation-messages.js';

// ----------------------------------------------------------
// DOM要素
// ----------------------------------------------------------
const formContents = document.getElementById('formContents');
const formSections = document.querySelectorAll('.form-section');
const formArea = document.getElementById('form-area');
const confirmArea = document.getElementById('confirm-area');
const completeArea = document.getElementById('complete-area');
const loadingArea = document.getElementById('formLoading');
const contactForm = document.getElementById('contact-form');

// ----------------------------------------------------------
// グローバルAPI
// ----------------------------------------------------------
let csrfToken = null;
let checkFormInstance = null;

// ----------------------------------------------------------
// 設定
// ----------------------------------------------------------
const API_BASE = contactForm.getAttribute('action');
const LANG = document.documentElement.lang || 'ja'; // HTML lang属性から言語を取得

// ----------------------------------------------------------
// ページ内関数
// ----------------------------------------------------------
// フォームデータを取得
function getFormData() {
  const formData = new FormData(contactForm);
  const data = {};

  // 通常のフィールドを取得
  formData.forEach((value, key) => {
    if (key !== 'attachment') {
      data[key] = value;
    }
  });

  return data;
}

// ファイル付きフォームデータを取得
function getFormDataWithFile() {
  const fd = new FormData(contactForm);

  // required属性が付いた要素のnameを送信（サーバー側で必須判定に使う）
  contactForm.querySelectorAll('input[required], select[required], textarea[required]').forEach((el) => {
    if (el.name) fd.append('__required[]', el.name);
  });

  return fd;
}

// エラー表示
function showErrors(errors) {
  // エラーメッセージをクリア
  document.querySelectorAll('.error-message').forEach((el) => {
    el.textContent = '';
    el.style.display = 'none';
  });

  // 各フィールドのエラーを表示（空文字のエラーは無視）
  for (const [field, message] of Object.entries(errors)) {
    if (!message) continue;
    const errorEl = document.querySelector(`[data-error="${field}"]`);
    if (errorEl) {
      errorEl.textContent = message;
      errorEl.style.display = 'block';
    }
  }
}

// ローディング表示切替
function toggleLoading(show) {
  if (show) {
    loadingArea.classList.add('show');
  } else {
    loadingArea.classList.remove('show');
  }
}

// エリア切替
async function showArea(area) {
  window.scrollTo(0, 0);

  formSections.forEach((el) => {
    el.classList.add('hide');
  });

  formContents.querySelectorAll('[data-target]').forEach((el, index) => {
    utils.removeAction(el);
  });

  switch (area) {
    case 'form':
      formArea.classList.remove('hide');
      break;

    case 'confirm':
      confirmArea.classList.remove('hide');
      break;

    case 'complete':
      completeArea.classList.remove('hide');
      break;

    default:
      break;
  }

  await utils.delay(100);
  formContents.querySelectorAll('[data-target]').forEach((el, index) => {
    utils.addAction(el, index);
  });
}

// 確認画面へ
async function handleSubmit(e) {
  e.preventDefault();

  // 送信ボタンがdisableの場合は処理を中断
  const submitBtn = contactForm.querySelector('.btn_submit');
  if (submitBtn && submitBtn.classList.contains('disable')) {
    return;
  }

  toggleLoading(true);
  showErrors({});

  try {
    const formData = getFormDataWithFile();
    formData.append('lang', LANG); // 言語パラメータを追加

    const response = await fetch(`${API_BASE}/validate.php`, {
      method: 'POST',
      body: formData, // multipart/form-dataとして送信
    });

    // レスポンスのチェック
    if (!response.ok) {
      const text = await response.text();
      console.error('Server error:', response.status, text);
      alert(`サーバーエラーが発生しました (${response.status})`);
      showArea('form');
      return;
    }

    const contentType = response.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
      const text = await response.text();
      console.error('Invalid response type:', contentType, text);
      alert('サーバーから不正なレスポンスが返されました');
      showArea('form');
      return;
    }

    const data = await response.json();

    if (data.status === 'confirm') {
      csrfToken = data.token;
      confirmArea.innerHTML = data.html;
      showArea('confirm');

      // 確認画面のボタンイベント設定
      setupConfirmButtons();
    } else if (data.status === 'error') {
      showErrors(data.errors);
      showArea('form');
    }
  } catch (error) {
    console.error('Error:', error);
    alert('通信エラーが発生しました。');
  } finally {
    toggleLoading(false);
  }
}

// 確認画面のボタン設定
function setupConfirmButtons() {
  // 戻るボタン
  const backBtn = confirmArea.querySelector('.btn.back');
  if (backBtn) {
    backBtn.addEventListener('click', () => {
      showArea('form');
    });
  }

  // 送信ボタン
  const sendBtn = confirmArea.querySelector('.btn.send');
  if (sendBtn) {
    sendBtn.addEventListener('click', handleSend);
  }
}

// メール送信
async function handleSend() {
  toggleLoading(true);

  try {
    const response = await fetch(`${API_BASE}/send.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        token: csrfToken,
      }),
    });

    // レスポンスのチェック
    if (!response.ok) {
      const text = await response.text();
      console.error('Server error:', response.status, text);
      alert(`サーバーエラーが発生しました (${response.status})`);
      showArea('form');
      return;
    }

    const contentType = response.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
      const text = await response.text();
      console.error('Invalid response type:', contentType, text);
      alert('サーバーから不正なレスポンスが返されました');
      showArea('form');
      return;
    }

    const data = await response.json();

    if (data.status === 'success') {
      completeArea.innerHTML = data.html;
      showArea('complete');
      contactForm.reset();
    } else if (data.status === 'error') {
      alert(data.message || '送信に失敗しました。');
      showArea('form');
    }
  } catch (error) {
    console.error('Error:', error);
    alert('通信エラーが発生しました。');
  } finally {
    toggleLoading(false);
  }
}

// フォーム種類の切り替え
function setupInquiryTypeToggle() {
  const inquiryTypeSelect = document.getElementById('inquiry-type');
  const recruitOnlyFields = document.querySelectorAll('.recruit-only');

  if (!inquiryTypeSelect) return; // フォーム選択がない場合はスキップ

  inquiryTypeSelect.addEventListener('change', (e) => {
    const isRecruit = e.target.value === 'recruit';

    recruitOnlyFields.forEach((group) => {
      const inputs = group.querySelectorAll('input, select, textarea');

      if (isRecruit) {
        group.style.display = 'block';
        // required属性を復元
        inputs.forEach((field) => {
          if (field.dataset.originalRequired === 'true') {
            field.required = true;
          }
        });
      } else {
        group.style.display = 'none';
        // required属性を一時削除
        inputs.forEach((field) => {
          if (field.required) {
            field.dataset.originalRequired = 'true';
            field.required = false;
          }
          // 値もクリア
          field.value = '';
        });
      }
    });

    // CheckFormを再実行
    checkFormInstance?.check();
  });

  // 初期状態で採用フォーム項目のrequired情報を保存
  recruitOnlyFields.forEach((group) => {
    group.querySelectorAll('input, select, textarea').forEach((field) => {
      if (field.required) {
        field.dataset.originalRequired = 'true';
        field.required = false; // 初期状態では非表示なので無効化
      }
    });
  });
}

// ----------------------------------------------------------
// 初期化
// ----------------------------------------------------------
async function quickSettings() {
  if (contactForm) {
    // CheckFormインスタンスを作成（バリデーション・送信ボタン制御）
    checkFormInstance = new CheckForm(contactForm, validationMessages);

    // フォーム種類の切り替え設定
    setupInquiryTypeToggle();

    // フォーム送信イベント
    contactForm.addEventListener('submit', handleSubmit);
  }
}

// ----------------------------------------------------------
// 実行
// ----------------------------------------------------------
window.addEventListener('DOMContentLoaded', () => {
  quickSettings();
});
