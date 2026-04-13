npm install sqlite sqlite3 bcrypt-- dememo 資料表：存放圖文備忘，包括新增者、文字內容、圖片與縮圖路徑
PRAGMA foreign_keys = ON; -- 啟用外鍵約束以維持 dbusers 參照完整性
CREATE TABLE IF NOT EXISTS dememo ( -- 建立圖文備忘資料表
  memo_id INTEGER PRIMARY KEY AUTOINCREMENT, -- 備忘唯一識別碼
  user_id INTEGER NOT NULL, -- 新增者使用者 ID
  title VARCHAR(100) NOT NULL, -- 備忘標題
  content TEXT NOT NULL, -- 多行文字備忘內容
  image_path VARCHAR(255), -- 原始圖片檔案路徑
  thumbnail_path VARCHAR(255), -- 縮圖檔案路徑
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- 建立時間
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, -- 更新時間
  FOREIGN KEY (user_id) REFERENCES dbusers(user_id) -- 關聯到 dbusers 資料表
); -- 結束 dememo 資料表建立

-- 範例圖文備忘資料：使用者、新增內容-- 範例圖文備忘資料：使用者、新增內容-- 範例圖文備忘資料：使用者、新增內容-- ? '讀書筆記', '今天完成第三章，整理重點如下：\n1. 資料庫設計\n2. 正規化\n3. 索引使用', '/images/memo1.jpg', '/thumbnails/memo1_thumb.jpg'); -- 使用者 A 新增備忘
INSERT INTO dememo (user_id, title, content, image_path, thumbnail_path) VALUES (2, '旅行計畫', '規劃澎湖三天兩夜行程：\n- 第一天：搭船、海邊散步\n- 第二天：浮潛\n- 第三天：美食採買', '/images/memo2.jpg', '/thumbnails/memo2_thumb.jpg'); -- 使用者 B 新增備忘
INSERT INTO dememo (user_id, title, content, image_path, thumbnail_path) VALUES (3, '健身日誌', '今天練習胸部與三頭肌，重量與組數記錄如下：\n- 卧推 60kg x 10\n- 飛鳥 12kg x 12', '/images/memo3.jpg', '/thumbnails/memo3_thumb.jpg'); -- 使用者 C 新增備忘

-- 常用圖文備忘操作示範
SELECT * FROM dememo; -- 列出所有圖文備忘
UPDATE dememo SET title = '旅行行程更新', updated_at = CURRENT_TIMESTAMP WHERE memo_id = 2; -- 修改備忘標題與更新時間
DELETE FROM dememo WHERE memo_id = 3; -- 刪除指定備忘

-- 登入日誌瀏覽示範
SELECT * FROM dblog; -- 檢視登入紀錄
