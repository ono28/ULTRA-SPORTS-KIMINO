// バリデーションメッセージ（JavaScript用）
export const validationMessages = {
  validation: {
    // 汎用メッセージ
    required: 'この項目は必須です。',
    invalid: '入力内容が正しくありません。',
    input_required: '入力してください。',
    select_required: '選択してください。',

    // テキストフォーム
    text_required: '入力してください。',
    text_minlength: '文字以上で入力してください。',
    text_maxlength: '文字以内で入力してください。',

    // メールアドレス
    email_required: 'メールアドレスを入力してください。',
    email_invalid: '正しいメールアドレスを入力してください。',

    // 電話番号
    tel_required: '電話番号を入力してください。',
    tel_invalid: '正しい電話番号を入力してください。',

    // 郵便番号
    zip_required: '郵便番号を入力してください。',
    zip_invalid: '正しい郵便番号を入力してください（例: 123-4567）。',

    // パスワード
    password_required: 'パスワードを入力してください。',
    password_minlength: 'パスワードは8文字以上で入力してください。',
    password_weak: 'パスワードは英数字を含めてください。',
    password_mismatch: 'パスワードが一致しません。',

    // URL
    url_required: 'URLを入力してください。',
    url_invalid: '正しいURL形式で入力してください（例: https://example.com）。',

    // 数値
    number_required: '数値を入力してください。',
    number_invalid: '半角数字で入力してください。',
    number_min: '以上の数値を入力してください。',
    number_max: '以下の数値を入力してください。',

    // 日付
    date_required: '日付を入力してください。',
    date_invalid: '正しい日付を入力してください。',

    // ラジオボタン
    radio_required: '選択してください。',

    // チェックボックス
    checkbox_required: '選択してください。',
    checkbox_agreement: '同意が必要です。',
    checkbox_minselect: '個以上選択してください。',
    checkbox_maxselect: '個以内で選択してください。',

    // セレクトボックス
    select_required: '選択してください。',

    // テキストエリア
    textarea_required: '入力してください。',
    textarea_minlength: '文字以上で入力してください。',
    textarea_maxlength: '文字以内で入力してください。',

    // ファイルアップロード
    file_required: 'ファイルを選択してください。',
    file_size: 'ファイルサイズが大きすぎます。',
    file_type: '許可されていないファイル形式です。',

    // 個別フィールド（既存フォーム用）
    name_required: 'お名前を入力してください。',
    message_required: 'お問い合せ内容を入力してください。',
    inquiry_type_required: 'お問い合わせ種類を選択してください。',
    position_required: '希望職種を入力してください。',
    privacy_required: '個人情報保護方針に同意してください。',
  },
};
