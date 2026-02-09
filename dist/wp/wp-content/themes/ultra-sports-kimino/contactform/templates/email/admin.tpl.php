<?php
// 管理者送信メール

// ============================================
// ヘッダー（自由に編集可能）
// ============================================
$header = <<<EOF
お問い合わせがありました。


EOF;

// ============================================
// フォーム項目（自動生成）
// ============================================
$body = "【お名前】\n";
$body .= $contactData['name'] . "\n\n";
$body .= "【電話番号】\n";
$body .= $contactData['tel'] . "\n\n";
$body .= "【メールアドレス】\n";
$body .= $contactData['email'] . "\n\n";

if (isset($contactData['inquiry_type'])) {
    $inquiryTypeText = $contactData['inquiry_type'] === 'general' ? '一般お問い合わせ' : '採用について';
    $body .= "【お問い合わせ種類】\n";
    $body .= $inquiryTypeText . "\n\n";
}

if (isset($contactData['position']) && !empty($contactData['position'])) {
    $body .= "【希望職種】\n";
    $body .= $contactData['position'] . "\n\n";
}

if (isset($contactData['experience']) && !empty($contactData['experience'])) {
    $body .= "【経験年数】\n";
    $body .= $contactData['experience'] . "\n\n";
}

$body .= "【お問合せ内容】\n";
$body .= $contactData['message'] . "\n\n";

// ============================================
// フッター（自由に編集可能）
// ============================================
$sendDate = date('Y年m月d日 H:i:s', $timestamp);
$footer = <<<EOF
---
送信日時: {$sendDate}

※このメールは自動送信されています
EOF;

echo $header . $body . $footer;