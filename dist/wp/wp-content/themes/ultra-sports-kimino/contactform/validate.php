<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// エラーハンドリング
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    // フォームデータ取得（multipart/form-data）
    $data = $_POST;
    $file = $_FILES['attachment'] ?? null;

    // 言語設定（デフォルトは日本語）
    $lang = $data['lang'] ?? 'ja';
    $langFile = __DIR__ . '/templates/lang/' . $lang . '.php';
    if (!file_exists($langFile)) {
        $lang = 'ja'; // フォールバック
        $langFile = __DIR__ . '/templates/lang/ja.php';
    }
    $messages = require $langFile;
    $_SESSION['lang'] = $lang; // セッションに保存

    // バリデーション
    $errors = [];

    // クライアントが送信したrequired属性一覧を取得（FormData内の__required[]）
    $requiredFields = $_POST['__required'] ?? [];
    if (!is_array($requiredFields)) {
        $requiredFields = [$requiredFields];
    }
    // validateループで処理しないように除去
    if (isset($data['__required'])) {
        unset($data['__required']);
    }

    // 送信された全フィールドをチェック（langとattachmentを除く）
    foreach ($data as $field => $value) {
        if ($field === 'lang') continue;

        // requiredでない（任意）かつ空ならスキップ
        if (!in_array($field, $requiredFields) && empty(trim($value))) {
            continue;
        }

        // 空値チェック（requiredフィールドのみ）
        if (in_array($field, $requiredFields) && empty(trim($value))) {
            // 個別フィールドメッセージ → 汎用メッセージの順でフォールバック
            $errors[$field] = $messages['validation'][$field . '_required']
                ?? $messages['validation']['input_required']
                ?? 'この項目は必須です。';
            continue;
        }

        // emailフィールドの形式チェック
        if ($field === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$field] = $messages['validation']['email_invalid'];
        }

        // telフィールドの形式チェック
        if ($field === 'tel' && !preg_match('/^[0-9\-]+$/', $value)) {
            $errors[$field] = $messages['validation']['tel_invalid'];
        }
    }

    // 添付ファイルのバリデーション
    $attachmentInfo = null;
    if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
        // ファイルがアップロードされた場合
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['attachment'] = 'ファイルのアップロードに失敗しました。';
        } else {
            // ファイルサイズチェック
            $maxSize = 5242880; // 5MB
            if ($file['size'] > $maxSize) {
                $errors['attachment'] = 'ファイルサイズは5MB以下にしてください。';
            }

            // 拡張子チェック
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                $errors['attachment'] = '許可されていないファイル形式です。';
            }

            // エラーがなければ一時保存
            if (empty($errors['attachment'])) {
                $uploadDir = sys_get_temp_dir() . '/contact_uploads/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0700, true);
                }

                $tempFileName = uniqid('upload_') . '_' . basename($file['name']);
                $tempFilePath = $uploadDir . $tempFileName;

                if (move_uploaded_file($file['tmp_name'], $tempFilePath)) {
                    $attachmentInfo = [
                        'name' => basename($file['name']),
                        'size' => $file['size'],
                        'type' => $file['type'],
                        'temp_path' => $tempFilePath
                    ];
                } else {
                    $errors['attachment'] = 'ファイルの保存に失敗しました。';
                }
            }
        }
    }

    // エラーがある場合
    if (!empty($errors)) {
        echo json_encode([
            'status' => 'error',
            'errors' => $errors
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // データをサニタイズして保存
    $_SESSION['contact_data'] = [];
    foreach ($data as $field => $value) {
        if ($field === 'lang') continue;
        $_SESSION['contact_data'][$field] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    $_SESSION['contact_data']['attachment'] = $attachmentInfo;

    // CSRFトークン生成
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['contact_timestamp'] = time();

    // 確認画面HTML生成
    // 各変数をテンプレート用に生成
    foreach ($_SESSION['contact_data'] as $key => $value) {
        if ($key === 'attachment') continue;
        if ($key === 'message') {
            $$key = nl2br($value); // messageは改行を<br>に変換
        } else {
            $$key = $value;
        }
    }
    $attachment = $_SESSION['contact_data']['attachment'] ?? null;

    // テンプレートファイルを読み込んでHTMLを生成
    ob_start();
    include __DIR__ . '/templates/confirm.tpl.php';
    $confirmHtml = ob_get_clean();

    echo json_encode([
        'status' => 'confirm',
        'html' => $confirmHtml,
        'token' => $_SESSION['csrf_token']
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $messages['error']['system_error'] ?? 'System error occurred.'
    ], JSON_UNESCAPED_UNICODE);
}