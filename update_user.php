<?php
// 更新 userB 暱稱為小甫
$db = new PDO('sqlite:mydatabase.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $stmt = $db->prepare("UPDATE dbusers SET nickname = ? WHERE account = ?");
    $stmt->execute(['小甫', 'userB']);
    
    echo "✅ 已成功將 userB 的暱稱更新為「小甫」<br><br>";
    
    // 顯示更新後的用戶資訊
    $stmt = $db->query("SELECT * FROM dbusers");
    echo "<h3>更新後的用戶列表：</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>帳號</th><th>暱稱</th><th>性別</th><th>興趣</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>{$row['account']}</td><td>{$row['nickname']}</td><td>{$row['gender']}</td><td>{$row['interests']}</td></tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "❌ 更新失敗：" . $e->getMessage();
}
?>