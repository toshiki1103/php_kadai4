<?php
// このファイルはセッション開始後に include される想定
// セッションが開始されていることを前提とする
?>

<nav style="background-color: #4CAF50; padding: 10px 20px; margin-bottom: 20px;">
    <div style="max-width: 800px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center;">
        <div style="color: white; font-weight: bold; font-size: 18px;">
            ⚽ リフティング記録アプリ
        </div>
        
        <div style="display: flex; gap: 20px; align-items: center;">
            <div style="color: white;">
                <?php echo h($_SESSION["name"]); ?>さん
                <?php if ($_SESSION["kanri_flg"] == 1): ?>
                    <span style="background-color: rgba(255,255,255,0.3); padding: 2px 8px; border-radius: 3px; font-size: 12px;">管理者</span>
                <?php else: ?>
                    <span style="background-color: rgba(255,255,255,0.3); padding: 2px 8px; border-radius: 3px; font-size: 12px;">一般</span>
                <?php endif; ?>
            </div>
            
            <a href="display2.php" style="color: white; text-decoration: none; padding: 8px 12px; background-color: rgba(0,0,0,0.2); border-radius: 4px; transition: 0.3s;">記録一覧</a>
            <a href="index.php" style="color: white; text-decoration: none; padding: 8px 12px; background-color: rgba(0,0,0,0.2); border-radius: 4px; transition: 0.3s;">新規記録</a>
            
            <?php if ($_SESSION["kanri_flg"] == 1): ?>
                <a href="user_manage.php" style="color: white; text-decoration: none; padding: 8px 12px; background-color: rgba(0,0,0,0.2); border-radius: 4px; transition: 0.3s;">ユーザー管理</a>
            <?php endif; ?>
            
            <a href="logout.php" style="color: white; text-decoration: none; padding: 8px 12px; background-color: #d32f2f; border-radius: 4px; transition: 0.3s;" onclick="return confirm('ログアウトしますか？');">ログアウト</a>
        </div>
    </div>
</nav>

<style>
    a[href*="logout"]:hover {
        background-color: #b71c1c !important;
    }
    a[href*="display2"], a[href*="index"], a[href*="user_manage"] {
        transition: background-color 0.3s;
    }
    a[href*="display2"]:hover, a[href*="index"]:hover, a[href*="user_manage"]:hover {
        background-color: rgba(0,0,0,0.4) !important;
    }
</style>
