<?php
// セッション開始と認証チェック
session_start();
include('funcs.php');
sschk();

$pdo = db_conn();

try {
    // 1. URLからIDを取得
    $id = $_GET["id"];
    
    // 2. 削除前に、ビデオファイルの情報を取得
    $sql_select = "SELECT video_filename FROM lifting_records WHERE id = :id";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt_select->execute();
    
    $row = $stmt_select->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $video_file = $row["video_filename"];
        
        // 3. ファイルシステムから動画ファイルを削除
        if (file_exists("uploads/" . $video_file)) {
            unlink("uploads/" . $video_file);
        }
        
        // 4. データベースからレコードを削除
        $sql_delete = "DELETE FROM lifting_records WHERE id = :id";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt_delete->execute();
    }
    
    // 5. 一覧ページにリダイレクト
    redirect("display2.php");
    
} catch (PDOException $e) {
    sql_error($stmt_delete);
}

?>
