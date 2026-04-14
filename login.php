<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 300px; margin: 0 auto; }
        input { display: block; width: 100%; margin-bottom: 10px; padding: 8px; }
        button { padding: 10px; width: 100%; background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>登入</h1>
    <form action="login.php" method="post">
        <input type="text" name="account" placeholder="帳號" required>
        <input type="password" name="password" placeholder="密碼" required>
        <button type="submit">登入</button>
    </form>
    <p>還沒有帳號？<a href="register.php">註冊</a></p>

    <?php
    session_start();
    if (isset($_SESSION['user_id'])) {
        header("Location: dashboard.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $account = $_POST['account'];
        $password = $_POST['password'];

        try {
            $db = new PDO('sqlite:mydatabase.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 檢查帳號密碼
            $stmt = $db->prepare("SELECT * FROM dbusers WHERE account = ?");
            $stmt->execute([$account]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $success = false;
            $remark = '';

            if ($user && $user['password'] == $password) { // 簡單比對，實際應使用雜湊
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['account'] = $user['account'];
                $_SESSION['nickname'] = $user['nickname'];
                $success = true;
                $remark = '登入成功';
                header("Location: dashboard.php");
                exit();
            } else {
                $remark = '密碼錯誤';
            }

            // 記錄登入日誌
            $stmt = $db->prepare("INSERT INTO dblog (user_id, account, success, remark) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user['user_id'] ?? null, $account, $success ? 1 : 0, $remark]);

            if (!$success) {
                echo "<p class='error'>登入失敗：$remark</p>";
            }

        } catch (PDOException $e) {
            echo "<p class='error'>資料庫錯誤: " . $e->getMessage() . "</p>";
        }
    }
    ?>
</body>
</html>