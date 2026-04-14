-- 匯出檔案：dememo_export.sql
PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS dememo (
  memo_id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  title VARCHAR(100) NOT NULL,
  content TEXT NOT NULL,
  image_path VARCHAR(255),
  thumbnail_path VARCHAR(255),
  status VARCHAR(20) DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES dbusers(user_id)
);

INSERT INTO dememo (user_id, title, content, image_path, thumbnail_path, status) VALUES
  (1, '讀書筆記', '今天完成第三章，整理重點如下：\n1. 資料庫設計\n2. 正規化\n3. 索引使用', '/images/memo1.jpg', '/thumbnails/memo1_thumb.jpg', 'active'),
  (2, '旅行計畫', '規劃澎湖三天兩夜行程：\n- 第一天：搭船、海邊散步\n- 第二天：浮潛\n- 第三天：美食採買', '/images/memo2.jpg', '/thumbnails/memo2_thumb.jpg', 'active'),
  (3, '健身日誌', '今天練習胸部與三頭肌，重量與組數記錄如下：\n- 卧推 60kg x 10\n- 飛鳥 12kg x 12', '/images/memo3.jpg', '/thumbnails/memo3_thumb.jpg', 'active');
