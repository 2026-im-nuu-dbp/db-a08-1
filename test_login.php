<?php
// 完整測試登入腳本 - 模擬多個用戶登入
session_start();

$db = new PDO('sqlite:mydatabase.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 測試用戶列表
$test_users = [
    ['account' => 'userA', 'password' => 'passA123'],
    ['account' => 'userB', 'password' => 'passB456'],
    ['account' => 'userC', 'password' => 'passC789'],
    ['account' => 'userA', 'password' => 'wrongpass'], // 測試失敗登入
    ['account' => 'nonexist', 'password' => 'anypass'], // 測試不存在用戶
];

echo "<h2>模擬多個用戶登入測試</h2>";

foreach ($test_users as $test_user) {
    $account = $test_user['account'];
    $password = $test_user['password'];

    $stmt = $db->prepare("SELECT * FROM dbusers WHERE account = ?");
    $stmt->execute([$account]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $success = false;
    $remark = '';

    if ($user && $user['password'] == $password) {
        $success = true;
        $remark = '登入成功';
        echo "✅ {$account} 登入成功<br>";
    } elseif ($user) {
        $remark = '密碼錯誤';
        echo "❌ {$account} 密碼錯誤<br>";
    } else {
        $remark = '帳號不存在';
        echo "❌ {$account} 帳號不存在<br>";
    }

    // 記錄登入日誌
    $stmt = $db->prepare("INSERT INTO dblog (user_id, account, success, remark) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user['user_id'] ?? null, $account, $success ? 1 : 0, $remark]);

    // 稍微延遲以確保時間不同
    usleep(100000); // 0.1秒
}

echo "<h2>當前系統時間：" . date('Y-m-d H:i:s') . "</h2>";

// 檢查日誌數量
$count = $db->query('SELECT COUNT(*) FROM dblog')->fetchColumn();
echo "<h3>目前登入記錄總數：$count</h3>";

// 顯示所有記錄
$stmt = $db->query("SELECT d.*, u.nickname FROM dblog d LEFT JOIN dbusers u ON d.user_id = u.user_id ORDER BY d.login_time DESC");
echo "<h3>所有登入記錄：</h3>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>帳號</th><th>暱稱</th><th>登入時間</th><th>成功</th><th>備註</th></tr>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $success_text = $row['success'] ? '成功' : '失敗';
    $color = $row['success'] ? 'green' : 'red';
    // 轉換時間為本地時間 (UTC+8)
    $local_time = date('Y-m-d H:i:s', strtotime($row['login_time']) + 8*3600);
    echo "<tr><td>{$row['log_id']}</td><td>{$row['account']}</td><td>{$row['nickname']}</td><td>$local_time</td><td style='color: $color'>$success_text</td><td>{$row['remark']}</td></tr>";
}
echo "</table>";
?>