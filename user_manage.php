<?php
// セッション開始と認証チェック
session_start();
include('funcs.php');
sschk();

// 管理者権限チェック
if ($_SESSION["kanri_flg"] != 1) {
    // 管理者ではない場合はアクセス拒否
    exit("管理者のみアクセスできます");
}

$pdo = db_conn();

// ユーザー一覧を取得
try {
    $sql = "SELECT id, name, lid, kanri_flg, life_flg FROM lifting_users ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    sql_error($stmt);
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー管理</title>
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
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
        }
        .badge-admin {
            background-color: #4CAF50;
            color: white;
        }
        .badge-user {
            background-color: #2196F3;
            color: white;
        }
        .badge-inactive {
            background-color: #9E9E9E;
            color: white;
        }
    </style>
</head>
<body>
    <!-- メニュー表示 -->
    <?php include('menu.php'); ?>

    <div class="container">
        <h1>👥 ユーザー管理</h1>
        <a href="user_create.php" class="btn">新規ユーザーを追加</a>
        
        <table border="1">
            <tr>
                <th>ID</th>
                <th>ユーザー名</th>
                <th>ログインID</th>
                <th>権限</th>
                <th>状態</th>
                <th>操作</th>
            </tr>
            
            <?php
            if (!empty($users)) {
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . h($user["id"]) . "</td>";
                    echo "<td>" . h($user["name"]) . "</td>";
                    echo "<td>" . h($user["lid"]) . "</td>";
                    echo "<td>";
                    if ($user["kanri_flg"] == 1) {
                        echo '<span class="badge badge-admin">管理者</span>';
                    } else {
                        echo '<span class="badge badge-user">一般</span>';
                    }
                    echo "</td>";
                    echo "<td>";
                    if ($user["life_flg"] == 0) {
                        echo '使用中';
                    } else {
                        echo '<span class="badge badge-inactive">退会</span>';
                    }
                    echo "</td>";
                    echo "<td>";
                    echo "<a href='user_edit.php?id=" . h($user["id"]) . "'>編集</a> | ";
                    echo "<a href='user_delete.php?id=" . h($user["id"]) . "' class='btn-delete' onclick=\"return confirm('本当に削除しますか？');\">削除</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>ユーザーがいません</td></tr>";
            }
            ?>
            
        </table>
        
    </div>
</body>
</html>
