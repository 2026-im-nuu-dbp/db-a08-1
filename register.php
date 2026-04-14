<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 300px; margin: 0 auto; }
        input, select { display: block; width: 100%; margin-bottom: 10px; padding: 8px; }
        button { padding: 10px; width: 100%; background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>註冊</h1>
    <form action="register.php" method="post">
        <input type="text" name="account" placeholder="帳號" required>
        <input type="text" name="nickname" placeholder="暱稱" required>
        <input type="password" name="password" placeholder="密碼" required>
        <select name="gender" required>
            <option value="">選擇性別</option>
            <option value="男">男</option>
            <option value="女">女</option>
            <option value="其他">其他</option>
        </select>
        <input type="text" name="interests" placeholder="興趣 (用逗號分隔)">
        <button type="submit">註冊</button>
    </form>
    <p>已有帳號？<a href="login.php">登入</a></p>

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
                echo "<p class='error'>帳號已存在</p>";
            } else {
                // 新增使用者
                $stmt = $db->prepare("INSERT INTO dbusers (account, nickname, password, gender, interests) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$account, $nickname, $password, $gender, $interests]);
                echo "<p>註冊成功！<a href='login.php'>前往登入</a></p>";
            }

        } catch (PDOException $e) {
            echo "<p class='error'>資料庫錯誤: " . $e->getMessage() . "</p>";
        }
    }
    ?>
</body>
</html>