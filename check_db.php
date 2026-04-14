<?php
try {
    $db = new PDO('sqlite:mydatabase.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h2>資料庫狀態檢查</h2>";

    // 檢查表
    $result = $db->query('SELECT name FROM sqlite_master WHERE type="table"');
    echo "<h3>現有資料表：</h3><ul>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>{$row['name']}</li>";
    }
    echo "</ul>";

    // 檢查資料
    $tables = ['dbusers', 'dblog', 'dememo'];
    foreach ($tables as $table) {
        $count = $db->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "<h3>$table 表資料數量：$count</h3>";

        if ($count > 0) {
            $stmt = $db->query("SELECT * FROM $table LIMIT 5");
            echo "<table border='1' style='border-collapse: collapse; margin-bottom: 20px;'>";
            $first = true;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($first) {
                    echo "<tr>";
                    foreach ($row as $key => $value) {
                        echo "<th style='padding: 5px;'>$key</th>";
                    }
                    echo "</tr>";
                    $first = false;
                }
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td style='padding: 5px;'>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
    }

} catch (Exception $e) {
    echo "<h2 style='color: red;'>資料庫錯誤：" . $e->getMessage() . "</h2>";
    echo "<p>請先運行 <a href='init.php'>初始化腳本</a></p>";
}
?>