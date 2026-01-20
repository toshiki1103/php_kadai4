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
    $name = $_POST["name"] ?? "";
    $lid = $_POST["lid"] ?? "";
    $lpw = $_POST["lpw"] ?? "";
    $kanri_flg = $_POST["kanri_flg"] ?? 0;

    // 2. バリデーション
    if (empty($name) || empty($lid) || empty($lpw)) {
        throw new Exception("すべての項目を入力してください");
    }

    // 3. パスワードをハッシュ化
    $hashed_pw = password_hash($lpw, PASSWORD_DEFAULT);

    // 4. ログインIDの重複確認
    $sql_check = "SELECT COUNT(*) as cnt FROM lifting_users WHERE lid = :lid";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindValue(':lid', $lid, PDO::PARAM_STR);
    $stmt_check->execute();
    $check = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($check["cnt"] > 0) {
        throw new Exception("このログインIDは既に使用されています");
    }

    // 5. ユーザーを作成
    $sql = "INSERT INTO lifting_users (name, lid, lpw, kanri_flg, life_flg) 
            VALUES (:name, :lid, :lpw, :kanri_flg, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
    $stmt->bindValue(':lpw', $hashed_pw, PDO::PARAM_STR);
    $stmt->bindValue(':kanri_flg', $kanri_flg, PDO::PARAM_INT);
    
    $stmt->execute();

    // 6. ユーザー一覧ページにリダイレクト
    redirect("user_manage.php");

} catch (Exception $e) {
    $_SESSION['login_error'] = 'エラー: ' . $e->getMessage();
    redirect("user_create.php");
} catch (PDOException $e) {
    sql_error($stmt);
}

?>
