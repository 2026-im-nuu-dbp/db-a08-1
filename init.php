<?php
// 初始化資料庫腳本
$db = new PDO('sqlite:mydatabase.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 執行 dbusers_export.sql
$sql = file_get_contents('0414/dbusers_export.sql');
$db->exec($sql);

// 執行 dblog_export.sql
$sql = file_get_contents('0414/dblog_export.sql');
$db->exec($sql);

// 執行 dememo_export.sql
$sql = file_get_contents('0414/dememo_export.sql');
$db->exec($sql);

echo "資料庫初始化完成！";
?>