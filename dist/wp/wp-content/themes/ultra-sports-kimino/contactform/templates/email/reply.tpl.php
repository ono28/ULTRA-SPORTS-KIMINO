<?php
// 自動返信メール

// ============================================
// ヘッダー（自由に編集可能）
// ============================================
$header = <<<EOF
{$contactData['name']} 様

この度はULTRA SOPRTS KIMINOへお問い合わせいただき誠にありがとうございます。
以下の内容のお問い合わせを受け付けました。
近日中に担当者より折り返しご連絡させていただきます。
尚、お問い合わせ内容によっては、ご返事までにお時間をいただく場合もございます。
あらかじめご了承ください。


EOF;

// ============================================
// フォーム項目（自動生成）
// ============================================
$body = "━━━　ご入力内容　━━━\n\n";
$body .= "【お名前】\n";
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
$body .= "━━━━━━━━━━━━━\n\n";

// ============================================
// フッター（自由に編集可能）
// ============================================
$sendDate = date('Y年m月d日 H:i:s', $timestamp);
$footer = <<<EOF
送信日時: {$sendDate}

このメールはULTRA SOPRTS KIMINOのWEBサイトからお問い合わせいただいた方へ自動送信しております。
お心当たりのない方は、恐れ入りますが下記へその旨をご連絡いただけますと幸いです。
━━━━━━━━━━━━━
ULTRA SOPRTS KIMINO
〒640-1141
和歌山県海草郡紀美野町動木518
MAIL: info@ultrasportskimino.jp
━━━━━━━━━━━━━

EOF;

echo $header . $body . $footer;