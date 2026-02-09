<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// エラーハンドリング
error_reporting(E_ALL);
ini_set('display_errors', 0);

// PHPMailer読み込み
require_once __DIR__ . '/src/PHPMailer.php';
require_once __DIR__ . '/src/SMTP.php';
require_once __DIR__ . '/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    // JSONデータ取得
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // 言語設定をセッションから取得
    $lang = $_SESSION['lang'] ?? 'ja';
    $langFile = __DIR__ . '/templates/lang/' . $lang . '.php';
    if (!file_exists($langFile)) {
        $lang = 'ja';
        $langFile = __DIR__ . '/templates/lang/ja.php';
    }
    $messages = require $langFile;

    // CSRFトークン検証
    if (!isset($_SESSION['csrf_token']) || !isset($data['token']) ||
        $_SESSION['csrf_token'] !== $data['token']) {
        throw new Exception($messages['error']['csrf']);
    }

    // セッションタイムアウトチェック (30分)
    if (!isset($_SESSION['contact_timestamp']) ||
        (time() - $_SESSION['contact_timestamp']) > 1800) {
        throw new Exception($messages['error']['timeout']);
    }

    // セッションデータ取得
    if (!isset($_SESSION['contact_data'])) {
        throw new Exception($messages['error']['no_data']);
    }

    $contactData = $_SESSION['contact_data'];

    // .envファイル読み込み（簡易版）
    $envPath = __DIR__ . '/templates/config/.env';
    if (file_exists($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
        }
    }

    // PHPMailer設定
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

    // SMTP設定がある場合はSMTP、ない場合はmail()関数を使用
    if (!empty($_ENV['SMTP_HOST'])) {
        // 外部SMTP使用（Gmail、SendGridなど）
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USERNAME'] ?? '';
        $mail->Password = $_ENV['SMTP_PASSWORD'] ?? '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['SMTP_PORT'] ?? 587;
    } else {
        // レンタルサーバーのmail()関数を使用
        $mail->isMail();
    }

    // 送信者・受信者設定
    $mail->setFrom($_ENV['MAIL_FROM'] ?? 'noreply@example.com', $_ENV['MAIL_FROM_NAME'] ?? 'Contact Form');
    $mail->addAddress($_ENV['MAIL_TO'] ?? 'info@example.com');
    $mail->addReplyTo($contactData['email'], $contactData['name']);

    // メール内容（テンプレートから生成）
    $adminSubject = $_ENV['ADMIN_EMAIL_SUBJECT'] ?? '[お問い合わせ] {subject}';
    $mail->Subject = str_replace('{subject}', $contactData['subject'], $adminSubject);
    $timestamp = $_SESSION['contact_timestamp'];
    ob_start();
    include __DIR__ . '/templates/email/admin.tpl.php';
    $mail->Body = ob_get_clean();

    // 添付ファイルがあれば、PHPMailerに添付
    if (!empty($contactData['attachment'])) {
        $attachment = $contactData['attachment'];
        if (file_exists($attachment['temp_path'])) {
            $mail->addAttachment($attachment['temp_path'], $attachment['name']);
        }
    }

    // メール送信
    $mail->send();

    // 添付ファイルの一時ファイルを削除
    if (!empty($contactData['attachment']) && file_exists($contactData['attachment']['temp_path'])) {
        unlink($contactData['attachment']['temp_path']);
    }

    // 自動返信メール（オプション）
    if ($_ENV['AUTO_REPLY'] ?? false) {
        $replyMail = new PHPMailer(true);
        $replyMail->CharSet = 'UTF-8';

        // SMTP設定がある場合はSMTP、ない場合はmail()関数を使用
        if (!empty($_ENV['SMTP_HOST'])) {
            $replyMail->isSMTP();
            $replyMail->Host = $_ENV['SMTP_HOST'];
            $replyMail->SMTPAuth = true;
            $replyMail->Username = $_ENV['SMTP_USERNAME'] ?? '';
            $replyMail->Password = $_ENV['SMTP_PASSWORD'] ?? '';
            $replyMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $replyMail->Port = $_ENV['SMTP_PORT'] ?? 587;
        } else {
            $replyMail->isMail();
        }

        $replyMail->setFrom($_ENV['MAIL_FROM'] ?? 'noreply@example.com', $_ENV['AUTO_REPLY_FROM_NAME'] ?? 'Auto Reply');
        $replyMail->addAddress($contactData['email']);
        $replyMail->Subject = $_ENV['AUTO_REPLY_SUBJECT'] ?? 'お問い合わせを受け付けました';

        // 自動返信メール本文（テンプレートから生成）
        ob_start();
        include __DIR__ . '/templates/email/reply.tpl.php';
        $replyMail->Body = ob_get_clean();

        $replyMail->send();
    }

    // セッションクリア（二重送信防止）
    unset($_SESSION['contact_data']);
    unset($_SESSION['csrf_token']);
    unset($_SESSION['contact_timestamp']);

    // 完了画面HTML（テンプレートファイルから生成）
    ob_start();
    include __DIR__ . '/templates/complete.tpl.php';
    $completeHtml = ob_get_clean();

    echo json_encode([
        'status' => 'success',
        'html' => $completeHtml,
        'message' => 'メールを送信しました。'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // エラーログ（本番環境では適切なロギング処理を実装）
    error_log('Contact form error: ' . $e->getMessage());

    // 言語ファイルの読み込み（エラー時）
    $lang = $_SESSION['lang'] ?? 'ja';
    $langFile = __DIR__ . '/templates/lang/' . $lang . '.php';
    if (file_exists($langFile)) {
        $messages = require $langFile;
        $errorMsg = $messages['error']['send_failed'];
    } else {
        $errorMsg = 'Failed to send email. Please try again later.';
    }

    echo json_encode([
        'status' => 'error',
        'message' => $errorMsg
    ], JSON_UNESCAPED_UNICODE);
}