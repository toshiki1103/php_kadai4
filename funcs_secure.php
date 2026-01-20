<?php

// =====================================================
// 関数1: h() - XSS対策関数
// =====================================================
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// =====================================================
// 関数2: db_conn() - データベース接続関数（.env 対応）
// =====================================================
function db_conn() {
    // .env ファイルから環境変数を読み込む
    if (file_exists('.env')) {
        $env = parse_ini_file('.env');
    } else {
        // .env ファイルがない場合は、環境変数から取得
        $env = array(
            'DB_HOST' => getenv('DB_HOST') ?: 'localhost',
            'DB_USER' => getenv('DB_USER') ?: 'root',
            'DB_PASSWORD' => getenv('DB_PASSWORD') ?: '',
            'DB_NAME' => getenv('DB_NAME') ?: 'database'
        );
    }
    
    $host = $env['DB_HOST'];
    $user = $env['DB_USER'];
    $password = $env['DB_PASSWORD'];
    $dbname = $env['DB_NAME'];
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "接続エラー: " . $e->getMessage();
        exit;
    }
}

// =====================================================
// 関数3: sql_error() - SQLエラー処理関数
// =====================================================
function sql_error($stmt) {
    $error = $stmt->errorInfo();
    exit('ErrorQuery: ' . $error[2]);
}

// =====================================================
// 関数4: redirect() - リダイレクト関数
// =====================================================
function redirect($file_name) {
    header('Location: ' . $file_name);
    exit();
}

// =====================================================
// 関数5: sschk() - セッションチェック関数
// =====================================================
function sschk() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"] != session_id()) {
        exit("LOGIN ERROR");
    } else {
        session_regenerate_id(true);
        $_SESSION["chk_ssid"] = session_id();
    }
}

?>
