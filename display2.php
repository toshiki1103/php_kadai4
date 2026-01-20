<?php
// セッション開始と認証チェック
session_start();
include('funcs.php');
sschk();

$pdo = db_conn();

// PDOを使用してデータベースからデータを取得
try {
    $sql = "SELECT id, record_date, count, video_filename FROM lifting_records ORDER BY record_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // すべてのデータを連想配列で取得
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    sql_error($stmt);
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>リフティング記録一覧</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .btn-delete {
            display: inline-block;
            padding: 8px 12px;
            background-color: #d32f2f;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-delete:hover {
            background-color: #b71c1c;
        }
    </style>
</head>
<body>
    <!-- メニュー表示 -->
    <?php include('menu.php'); ?>

    <div class="container">
        <h1>⚽ リフティング記録一覧</h1>
        <a href="index.php" class="btn">新規記録を追加</a>
        
        <table border="1">
            <tr>
                <th>日付</th>
                <th>回数</th>
                <th>動画</th>
                <th>操作</th>
            </tr>
            
            <?php
            // データがある場合は表示
            if (!empty($records)) {
                foreach ($records as $row) {
                    echo "<tr>";
                    echo "<td>" . h($row["record_date"]) . "</td>";
                    echo "<td>" . h($row["count"]) . "回</td>";
                    echo "<td>";
                    echo "<video width='200' controls>";
                    echo "<source src='uploads/" . h($row["video_filename"]) . "' type='video/mp4'>";
                    echo "</video>";
                    echo "</td>";
                    // 削除ボタン
                    echo "<td>";
                    echo "<a href='delete.php?id=" . h($row["id"]) . "' class='btn-delete' onclick=\"return confirm('本当に削除しますか？');\">削除</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>記録がありません</td></tr>";
            }
            ?>
            
        </table>
        
    </div>
</body>
</html>
