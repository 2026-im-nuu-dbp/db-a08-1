<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>儀表板</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .memo { border: 1px solid #ddd; padding: 10px; margin-bottom: 10px; }
        form { margin-bottom: 20px; }
        input, textarea { display: block; width: 100%; margin-bottom: 10px; padding: 8px; }
        button { padding: 10px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
        .logout { float: right; }
    </style>
</head>
<body>
    <h1>歡迎, <?php echo $_SESSION['nickname']; ?>!</h1>
    <a href="logout.php" class="logout">登出</a>

    <h2>新增圖文備忘</h2>
    <form action="dashboard.php" method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="標題" required>
        <textarea name="content" placeholder="內容" rows="5" required></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit" name="add_memo">新增備忘</button>
    </form>

    <h2>我的圖文備忘</h2>
    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $db = new PDO('sqlite:mydatabase.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 新增備忘
    if (isset($_POST['add_memo'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $user_id = $_SESSION['user_id'];

        $image_path = '';
        $thumbnail_path = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $image_path = $upload_dir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

            // 簡單縮圖 (實際應使用圖片處理庫)
            $thumbnail_path = $upload_dir . 'thumb_' . basename($_FILES['image']['name']);
            copy($image_path, $thumbnail_path); // 這裡只是複製，實際應縮小
        }

        $stmt = $db->prepare("INSERT INTO dememo (user_id, title, content, image_path, thumbnail_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $title, $content, $image_path, $thumbnail_path]);
        header("Location: dashboard.php");
        exit();
    }

    // 刪除備忘
    if (isset($_GET['delete'])) {
        $memo_id = $_GET['delete'];
        $stmt = $db->prepare("DELETE FROM dememo WHERE memo_id = ? AND user_id = ?");
        $stmt->execute([$memo_id, $_SESSION['user_id']]);
        header("Location: dashboard.php");
        exit();
    }

    // 顯示備忘
    $stmt = $db->prepare("SELECT * FROM dememo WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    while ($memo = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div class='memo'>";
        echo "<h3>{$memo['title']}</h3>";
        echo "<p>" . nl2br(htmlspecialchars($memo['content'])) . "</p>";
        if ($memo['image_path']) {
            echo "<img src='{$memo['thumbnail_path']}' alt='縮圖' style='max-width: 200px;'>";
        }
        echo "<p><small>建立時間: {$memo['created_at']}</small></p>";
        echo "<a href='dashboard.php?delete={$memo['memo_id']}'>刪除</a>";
        echo "</div>";
    }
    ?>
</body>
</html>