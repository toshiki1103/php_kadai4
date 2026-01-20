<?php
// セッション開始（必須！）
session_start();

// funcs.php を読み込み
include('funcs.php');

// 1. POSTデータを取得
$lid = $_POST["lid"] ?? "";
$lpw = $_POST["lpw"] ?? "";

// 2. DB接続
$pdo = db_conn();

try {
    // 3. ログインIDからユーザー情報を取得
    $sql = "SELECT * FROM lifting_users WHERE lid = :lid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
    $stmt->execute();
    
    // 4. ユーザーが存在するか確認
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        // 5. パスワード検証（password_verify()を使用）
        if (password_verify($lpw, $row["lpw"])) {
            // ✅ ログイン成功
            // セッションにユーザー情報を保存
            $_SESSION["chk_ssid"] = session_id();
            $_SESSION["user_id"] = $row['id'];
            $_SESSION["name"] = $row['name'];
            $_SESSION["kanri_flg"] = $row['kanri_flg'];
            
            // セッションIDを再生成（セッションハイジャック対策）
            session_regenerate_id(true);
            $_SESSION["chk_ssid"] = session_id();
            
            // ログイン成功後、トップページにリダイレクト
            redirect("display2.php");
        } else {
            // ❌ パスワード不正
            $_SESSION['login_error'] = 'ログインIDまたはパスワードが正しくありません。';
            redirect("login.php");
        }
    } else {
        // ❌ ログインIDが存在しない
        $_SESSION['login_error'] = 'ログインIDまたはパスワードが正しくありません。';
        redirect("login.php");
    }
    
} catch (PDOException $e) {
    sql_error($stmt);
}
?>
