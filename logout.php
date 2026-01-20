<?php
// セッション開始
session_start();

// セッション内容を初期化（空にする）
$_SESSION = array();

// クッキーに保存されているセッションIDを破棄
// セッション保存期間を過去に設定
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// サーバー側のセッションを完全に破棄
session_destroy();

// ログインページにリダイレクト
header("Location: login.php");
exit();
?>
