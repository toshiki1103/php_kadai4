<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン - リフティング記録アプリ</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .login-container h1 {
            margin-bottom: 30px;
            font-size: 24px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-login:hover {
            background-color: #45a049;
        }
        .error-message {
            color: #d32f2f;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #d32f2f;
            border-radius: 4px;
            background-color: #ffebee;
        }
        .info {
            margin-top: 20px;
            padding: 15px;
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
        }
        .info p {
            margin: 5px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>⚽ リフティング記録アプリ</h1>
        <h2 style="text-align: center; margin-bottom: 30px; color: #666; font-size: 18px;">ログイン</h2>

        <?php
        // セッションエラーメッセージを表示
        session_start();
        if (isset($_SESSION['login_error'])) {
            echo '<div class="error-message">' . h($_SESSION['login_error']) . '</div>';
            unset($_SESSION['login_error']);
        }
        
        // XSS対策関数
        function h($string) {
            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        }
        ?>

        <form action="login_act.php" method="POST">
            <div class="form-group">
                <label for="lid">ログインID</label>
                <input type="text" id="lid" name="lid" required autofocus>
            </div>

            <div class="form-group">
                <label for="lpw">パスワード</label>
                <input type="password" id="lpw" name="lpw" required>
            </div>

            <button type="submit" class="btn-login">ログイン</button>
        </form>

        <div class="info">
            <p><strong>テストアカウント:</strong></p>
            <p>ID: test1 / PW: test1（管理者）</p>
            <p>ID: test2 / PW: test2（一般ユーザー）</p>
        </div>
    </div>
</body>
</html>
