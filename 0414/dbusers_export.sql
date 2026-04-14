-- 匯出檔案：dbusers_export.sql
PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS dbusers (
  user_id INTEGER PRIMARY KEY AUTOINCREMENT,
  account VARCHAR(50) NOT NULL UNIQUE,
  nickname VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  gender VARCHAR(10) NOT NULL,
  interests TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO dbusers (account, nickname, password, gender, interests) VALUES
  ('userA', '小明', 'passA123', '男', '閱讀, 運動'),
  ('userB', '小華', 'passB456', '女', '電影, 音樂'),
  ('userC', '小美', 'passC789', '其他', '旅行, 美食');
