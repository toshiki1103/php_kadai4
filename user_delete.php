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
    // 1. URLからIDを取得
    $id = $_GET["id"] ?? "";

    if (empty($id)) {
        throw new Exception("ユーザーIDが指定されていません");
    }

    // 2. 自分自身は削除できないようにチェック
    if ($id == $_SESSION["user_id"]) {
        throw new Exception("自分自身は削除できません");
    }

    // 3. データベースからユーザーを削除
    $sql = "DELETE FROM lifting_users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
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
