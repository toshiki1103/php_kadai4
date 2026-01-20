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

// ユーザーIDを取得
$user_id = $_GET["id"] ?? "";

if (empty($user_id)) {
    exit("ユーザーIDが指定されていません");
}

// ユーザー情報を取得
try {
    $sql = "SELECT * FROM lifting_users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        exit("ユーザーが見つかりません");
    }
} catch (PDOException $e) {
    sql_error($stmt);
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー編集</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- メニュー表示 -->
    <?php include('menu.php'); ?>

    <div class="container">
        <h1>👥 ユーザー編集</h1>
        
        <form action="user_edit_act.php" method="post">
            <div class="form-group">
                <label for="name">ユーザー名</label>
                <input type="text" id="name" name="name" value="<?php echo h($user["name"]); ?>" required>
            </div>

            <div class="form-group">
                <label for="lid">ログインID</label>
                <input type="text" id="lid" name="lid" value="<?php echo h($user["lid"]); ?>" readonly style="background-color: #f5f5f5;">
            </div>

            <div class="form-group">
                <label for="lpw">パスワード（新しいパスワードを入力する場合のみ）</label>
                <input type="password" id="lpw" name="lpw">
            </div>

            <div class="form-group">
                <label>権限</label>
                <div style="margin-top: 10px;">
                    <label>
                        <input type="radio" name="kanri_flg" value="0" <?php if ($user["kanri_flg"] == 0) echo "checked"; ?>> 一般ユーザー
                    </label>
                    <br>
                    <label style="margin-top: 8px;">
                        <input type="radio" name="kanri_flg" value="1" <?php if ($user["kanri_flg"] == 1) echo "checked"; ?>> 管理者
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>ステータス</label>
                <div style="margin-top: 10px;">
                    <label>
                        <input type="radio" name="life_flg" value="0" <?php if ($user["life_flg"] == 0) echo "checked"; ?>> 使用中
                    </label>
                    <br>
                    <label style="margin-top: 8px;">
                        <input type="radio" name="life_flg" value="1" <?php if ($user["life_flg"] == 1) echo "checked"; ?>> 退会
                    </label>
                </div>
            </div>

            <input type="hidden" name="id" value="<?php echo h($user["id"]); ?>">

            <button type="submit" class="btn">更新する</button>
            <a href="user_manage.php" class="btn" style="background-color: #9E9E9E; margin-top: 10px;">キャンセル</a>
        </form>
    </div>
</body>
</html>
