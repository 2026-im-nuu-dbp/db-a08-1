<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入日誌管理</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #95a5a6;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .back-link:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        tr:hover {
            background: #e3f2fd;
            transition: background 0.3s ease;
        }
        .success {
            color: #27ae60;
            font-weight: bold;
        }
        .failure {
            color: #e74c3c;
            font-weight: bold;
        }
        .error {
            color: #e74c3c;
            background: #ffeaea;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            border-left: 4px solid #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>登入日誌管理</h1>
        <a href="index.php" class="back-link">← 回到首頁</a>

        <table>
            <tr><th>ID</th><th>帳號</th><th>暱稱</th><th>登入時間</th><th>狀態</th><th>備註</th></tr>
            <?php
            try {
                $db = new PDO('sqlite:mydatabase.db');
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $db->query("SELECT d.*, u.nickname FROM dblog d LEFT JOIN dbusers u ON d.user_id = u.user_id ORDER BY d.login_time DESC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $status_class = $row['success'] ? 'success' : 'failure';
                    $status_text = $row['success'] ? '成功' : '失敗';
                    // 轉換時間為本地時間 (假設 UTC+8)
                    $local_time = date('Y-m-d H:i:s', strtotime($row['login_time']) + 8*3600);
                    echo "<tr><td>{$row['log_id']}</td><td>{$row['account']}</td><td>{$row['nickname']}</td><td>$local_time</td><td class='$success_class'>$status_text</td><td>{$row['remark']}</td></tr>";
                }

            } catch (PDOException $e) {
                echo "<div class='error'>資料庫錯誤: " . $e->getMessage() . "</div>";
            }
            ?>
        </table>
    </div>
</body>
</html>