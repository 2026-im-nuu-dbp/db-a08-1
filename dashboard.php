<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>個人儀表板</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
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
        .logout {
            float: right;
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .logout:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        h2 {
            color: #444;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
            margin-top: 40px;
            margin-bottom: 20px;
        }
        form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
        }
        input, textarea {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.3);
        }
        button {
            padding: 12px 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .memo {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .memo:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }
        .memo h3 {
            color: #333;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .memo p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .memo img {
            max-width: 200px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .memo small {
            color: #999;
            font-style: italic;
        }
        .memo-actions {
            margin-top: 15px;
        }
        .memo-actions a {
            display: inline-block;
            margin-right: 10px;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .edit-link {
            background: #f39c12;
            color: white;
        }
        .edit-link:hover {
            background: #e67e22;
            transform: translateY(-1px);
        }
        .delete-link {
            background: #e74c3c;
            color: white;
        }
        .delete-link:hover {
            background: #c0392b;
            transform: translateY(-1px);
        }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="container">
        <h1>歡迎, <?php echo $_SESSION['nickname']; ?>!</h1>
        <div style="text-align: right; margin-bottom: 20px;">
            <a href="view_logs.php" style="background: #9b59b6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 10px; font-weight: bold; margin-right: 10px;">查看登入日誌</a>
            <a href="logout.php" class="logout">登出</a>
        </div>
        <div class="clear"></div>

    <h2>新增圖文備忘</h2>
    <form action="dashboard.php" method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="標題" required>
        <textarea name="content" placeholder="內容" rows="5" required></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit" name="add_memo">新增備忘</button>
    </form>

    <?php
    // 編輯備忘
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $stmt = $db->prepare("SELECT * FROM dememo WHERE memo_id = ? AND user_id = ?");
        $stmt->execute([$edit_id, $_SESSION['user_id']]);
        $edit_memo = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($edit_memo) {
            echo "<h2>編輯圖文備忘</h2>";
            echo "<form action='dashboard.php' method='post' enctype='multipart/form-data'>";
            echo "<input type='hidden' name='memo_id' value='{$edit_memo['memo_id']}'>";
            echo "<input type='text' name='title' value='" . htmlspecialchars($edit_memo['title']) . "' required>";
            echo "<textarea name='content' rows='5' required>" . htmlspecialchars($edit_memo['content']) . "</textarea>";
            echo "<input type='file' name='image' accept='image/*'>";
            echo "<button type='submit' name='edit_memo'>更新備忘</button>";
            echo "</form>";
        }
    }
    ?>

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

    // 編輯備忘
    if (isset($_POST['edit_memo'])) {
        $memo_id = $_POST['memo_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];

        $image_path = '';
        $thumbnail_path = '';

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $image_path = $upload_dir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

            $thumbnail_path = $upload_dir . 'thumb_' . basename($_FILES['image']['name']);
            copy($image_path, $thumbnail_path);
        }

        if ($image_path) {
            $stmt = $db->prepare("UPDATE dememo SET title = ?, content = ?, image_path = ?, thumbnail_path = ?, updated_at = CURRENT_TIMESTAMP WHERE memo_id = ? AND user_id = ?");
            $stmt->execute([$title, $content, $image_path, $thumbnail_path, $memo_id, $_SESSION['user_id']]);
        } else {
            $stmt = $db->prepare("UPDATE dememo SET title = ?, content = ?, updated_at = CURRENT_TIMESTAMP WHERE memo_id = ? AND user_id = ?");
            $stmt->execute([$title, $content, $memo_id, $_SESSION['user_id']]);
        }
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
        echo "<div class='memo-actions'>";
        echo "<a href='dashboard.php?edit={$memo['memo_id']}' class='edit-link'>編輯</a>";
        echo "<a href='dashboard.php?delete={$memo['memo_id']}' class='delete-link' onclick='return confirm(\"確定要刪除這個備忘嗎？\")'>刪除</a>";
        echo "</div>";
        echo "</div>";
    }
    ?>
    </div>
</body>
</html>