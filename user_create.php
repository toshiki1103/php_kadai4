<?php
// セッション開始と認証チェック
session_start();
include('funcs.php');
sschk();

// 管理者権限チェック
if ($_SESSION["kanri_flg"] != 1) {
    exit("管理者のみアクセスできます");
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規ユーザー作成</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- メニュー表示 -->
    <?php include('menu.php'); ?>

    <div class="container">
        <h1>👥 新規ユーザー作成</h1>
        
        <form action="user_create_act.php" method="post">
            <div class="form-group">
                <label for="name">ユーザー名</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="lid">ログインID</label>
                <input type="text" id="lid" name="lid" required>
            </div>

            <div class="form-group">
                <label for="lpw">パスワード</label>
                <input type="password" id="lpw" name="lpw" required>
            </div>

            <div class="form-group">
                <label>権限</label>
                <div style="margin-top: 10px;">
                    <label>
                        <input type="radio" name="kanri_flg" value="0" checked> 一般ユーザー
                    </label>
                    <br>
                    <label style="margin-top: 8px;">
                        <input type="radio" name="kanri_flg" value="1"> 管理者
                    </label>
                </div>
            </div>

            <button type="submit" class="btn">ユーザーを作成</button>
            <a href="user_manage.php" class="btn" style="background-color: #9E9E9E; margin-top: 10px;">キャンセル</a>
        </form>
    </div>
</body>
</html>
