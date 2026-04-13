-- dblog 資料表：記錄登入日誌，包括登入者帳號、時間與是否成功
PRAGMA foreign_keys = ON; -- 啟用外鍵約束以維持 dbusers 參照完整性
CREATE TABLE IF NOT EXISTS dblog ( -- 建立登入日誌資料表
  log_id INTEGER PRIMARY KEY AUTOINCREMENT, -- 日誌唯一識別碼
  user_id INTEGER NOT NULL, -- 所屬使用者 ID
  account VARCHAR(50) NOT NULL, -- 登入者帳號
  login_time DATETIME DEFAULT CURRENT_TIMESTAMP, -- 登入時間
  success BOOLEAN NOT NULL, -- 是否登入成功
  remark TEXT, -- 登入備註（例如失敗原因）
  FOREIGN KEY (user_id) REFERENCES dbusers(user_id) -- 關聯到 dbusers 資料表
); -- 結束 dblog 資料表建立

-- 範例登入記?- 範例登入記?- 範例登入記?- 範例登入記?- 範例登入記?- 範例登入記?- 範例登入記?- 範例登入記?- 範例登入記?- 範例登入記?- 範例登入記?- 範例登入記?- 範例登入記?- 範例登入記?- 範例登入記?SERT INTO dblog (user_id, account, success, remark) VALUES (3, 'userC', 1, '登入成功'); -- 使用者 C 登入成功

