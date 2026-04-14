<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入日誌</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>登入日誌</h1>
    <a href="index.php">回到首頁</a>

    <table>
        <tr><th>ID</th><th>帳號</th><th>登入時間</th><th>成功</th><th>備註</th><th>暱稱</th></tr>
        <?php
        try {
            $db = new PDO('sqlite:mydatabase.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $db->query("SELECT d.*, u.nickname FROM dblog d LEFT JOIN dbusers u ON d.user_id = u.user_id ORDER BY d.login_time DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $success = $row['success'] ? '是' : '否';
                echo "<tr><td>{$row['log_id']}</td><td>{$row['account']}</td><td>{$row['login_time']}</td><td>{$success}</td><td>{$row['remark']}</td><td>{$row['nickname']}</td></tr>";
            }

        } catch (PDOException $e) {
            echo "<p>資料庫錯誤: " . $e->getMessage() . "</p>";
        }
        ?>
    </table>
</body>
</html>