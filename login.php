<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入帳號</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input {
            margin-bottom: 20px;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
        }
        button {
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .error {
            color: #e74c3c;
            background: #ffeaea;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #e74c3c;
        }
        p {
            text-align: center;
            margin-top: 20px;
        }
        a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>登入帳號</h1>
        <form action="login.php" method="post">
            <input type="text" name="account" placeholder="帳號" required>
            <input type="password" name="password" placeholder="密碼" required>
            <button type="submit">立即登入</button>
        </form>
        <p>還沒有帳號？<a href="register.php">立即註冊</a></p>

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
                echo "<div class='error'>登入失敗：$remark</div>";
            }

        } catch (PDOException $e) {
            echo "<p class='error'>資料庫錯誤: " . $e->getMessage() . "</p>";
        }
    }
    ?>
</body>
</html>