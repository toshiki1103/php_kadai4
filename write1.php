<?php
// セッション開始と認証チェック
session_start();
include('funcs.php');
sschk();

$pdo = db_conn();

try {
    // 1. POSTデータを取得
    $record_date = $_POST["record_date"];
    $count = $_POST["count"];

    // 2. 動画ファイルを uploads フォルダに保存
    $video_file = $_FILES["video"]["name"];
    $tmp_file = $_FILES["video"]["tmp_name"];

    // ファイル名をサニタイズ（セキュリティ対策）
    $video_file = time() . '_' . basename($video_file);

    // ファイルをアップロード
    if (!move_uploaded_file($tmp_file, "uploads/" . $video_file)) {
        throw new Exception("ファイルのアップロードに失敗しました");
    }

    // 3. PDOを使用してプリペアドステートメントでデータベースに挿入
    $sql = "INSERT INTO lifting_records (record_date, count, video_filename) 
            VALUES (:date, :count, :filename)";
    $stmt = $pdo->prepare($sql);
    
    // bindValue()を使ってパラメータをバインド
    $stmt->bindValue(':date', $record_date, PDO::PARAM_STR);
    $stmt->bindValue(':count', $count, PDO::PARAM_INT);
    $stmt->bindValue(':filename', $video_file, PDO::PARAM_STR);
    
    // SQL実行
    $stmt->execute();
    
    // 表示ページにリダイレクト
    redirect("display2.php");
} catch (Exception $e) {
    // エラー時はログイン画面へ
    $_SESSION['login_error'] = 'エラーが発生しました: ' . $e->getMessage();
    redirect("login.php");
} catch (PDOException $e) {
    sql_error($stmt);
}
?>
