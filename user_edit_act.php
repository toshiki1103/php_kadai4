<?php
// セッション開始と認証チェック
session_start();
include('funcs.php');
sschk();

// 管理者権限チェック
if ($_SESSION["kanri_flg"] != 1) {
    exit("管理者のみアクセスできます");
}

$pdo = db_conn();

try {
    // 1. POSTデータを取得
    $id = $_POST["id"] ?? "";
    $name = $_POST["name"] ?? "";
    $kanri_flg = $_POST["kanri_flg"] ?? 0;
    $life_flg = $_POST["life_flg"] ?? 0;
    $lpw = $_POST["lpw"] ?? "";

    // 2. バリデーション
    if (empty($id) || empty($name)) {
        throw new Exception("必須項目を入力してください");
    }

    // 3. パスワードが入力されている場合はハッシュ化
    if (!empty($lpw)) {
        $sql = "UPDATE lifting_users SET name=:name, lpw=:lpw, kanri_flg=:kanri_flg, life_flg=:life_flg WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $hashed_pw = password_hash($lpw, PASSWORD_DEFAULT);
        $stmt->bindValue(':lpw', $hashed_pw, PDO::PARAM_STR);
    } else {
        $sql = "UPDATE lifting_users SET name=:name, kanri_flg=:kanri_flg, life_flg=:life_flg WHERE id=:id";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':kanri_flg', $kanri_flg, PDO::PARAM_INT);
    $stmt->bindValue(':life_flg', $life_flg, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
    $stmt->execute();

    // 4. ユーザー一覧ページにリダイレクト
    redirect("user_manage.php");

} catch (Exception $e) {
    $_SESSION['login_error'] = 'エラー: ' . $e->getMessage();
    redirect("user_manage.php");
} catch (PDOException $e) {
    sql_error($stmt);
}

?>
