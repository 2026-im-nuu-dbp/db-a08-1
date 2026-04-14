<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊帳號</title>
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
        input, select {
            margin-bottom: 20px;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input:focus, select:focus {
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
        .success {
            color: #27ae60;
            background: #eaffea;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #27ae60;
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
        .form-group {
            position: relative;
        }
        .form-group input::placeholder {
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>註冊新帳號</h1>
    <form action="register.php" method="post">
            <div class="form-group">
                <input type="text" name="account" placeholder="帳號" required>
            </div>
            <div class="form-group">
                <input type="text" name="nickname" placeholder="暱稱" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="密碼" required>
            </div>
            <div class="form-group">
                <select name="gender" required>
                    <option value="">選擇性別</option>
                    <option value="男">男</option>
                    <option value="女">女</option>
                    <option value="其他">其他</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="interests" placeholder="興趣 (用逗號分隔)">
            </div>
            <button type="submit">立即註冊</button>
        </form>
        <p>已有帳號？<a href="login.php">立即登入</a></p>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $account = $_POST['account'];
        $nickname = $_POST['nickname'];
        $password = $_POST['password'];
        $gender = $_POST['gender'];
        $interests = $_POST['interests'];

        try {
            $db = new PDO('sqlite:mydatabase.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 檢查帳號是否已存在
            $stmt = $db->prepare("SELECT * FROM dbusers WHERE account = ?");
            $stmt->execute([$account]);
            if ($stmt->fetch()) {
                echo "<div class='error'>帳號已存在，請選擇其他帳號</div>";
            } else {
                // 新增使用者
                $stmt = $db->prepare("INSERT INTO dbusers (account, nickname, password, gender, interests) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$account, $nickname, $password, $gender, $interests]);
                echo "<div class='success'>註冊成功！<a href='login.php'>前往登入</a></div>";
            }

        } catch (PDOException $e) {
            echo "<div class='error'>系統錯誤，請稍後再試</div>";
        }
    }
    ?>
</body>
</html>