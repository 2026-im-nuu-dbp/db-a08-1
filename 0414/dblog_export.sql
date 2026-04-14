-- 匯出檔案：dblog_export.sql
PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS dblog (
  log_id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER,
  account VARCHAR(50) NOT NULL,
  login_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  success BOOLEAN NOT NULL,
  remark TEXT,
  FOREIGN KEY (user_id) REFERENCES dbusers(user_id)
);

INSERT INTO dblog (user_id, account, success, remark) VALUES
  (1, 'userA', 1, '登入成功'),
  (2, 'userB', 1, '登入成功'),
  (NULL, 'notexists', 0, '帳號不存在'),
  (3, 'userC', 0, '密碼錯誤');
