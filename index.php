<?php
// セッション開始と認証チェック
session_start();
include('funcs.php');
sschk(); // ログインしていなければここで処理が止まる
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>リフティング記録</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- メニュー表示 -->
    <?php include('menu.php'); ?>

    <div class="container">
        <h1>⚽ リフティング記録</h1>
        <form action="write1.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="record_date">日付</label>
                <input type="date" id="record_date" name="record_date" required>
            </div>

            <div class="form-group">
                <label for="count">回数</label>
                <input type="number" id="count" name="count" min="0" required>
            </div>

            <div class="form-group">
                <label for="video">動画ファイル</label>
                <input type="file" id="video" name="video" accept="video/*" required>
            </div>

            <button type="submit" class="btn">記録を保存</button>
        </form>
    </div>
</body>
</html>
