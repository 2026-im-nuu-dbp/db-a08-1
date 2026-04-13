-- dbusers 資料表：存放註冊帳號、暱稱、密碼、性別、興趣與註冊時間
PRAGMA foreign_keys = ON; -- 啟用外鍵約束以維護資料完整性
CREATE TABLE IF NOT EXISTS dbusers ( -- 建立使用者註冊資料表
  user_id INTEGER PRIMARY KEY AUTOINCREMENT, -- 使用者唯一識別碼
  account VARCHAR(50) NOT NULL UNIQUE, -- 使用者帳號
  nickname VARCHAR(50) NOT NULL, -- 使用者暱稱
  password VARCHAR(255) NOT NULL, -- 使用者密碼（實務上應儲存雜湊值）
  gender VARCHAR(10) NOT NULL, -- 使用者性別
  interests TEXT, -- 使用者興趣清單
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP -- 註冊時間
); -- 結束 dbusers 資料表建立

-- 範例註冊資料：帳號、暱稱、密碼、性別、興趣
INSERT INTO dbusers (account, nickname, password, gender, interests) VALUES ('userA', '小明', 'passA123', '男', '閱讀, 運動'); -- 測試使用者 A
INSERT INTO dbusers (account, nickname, password, gender, interests) VALUES ('userB', '小華', 'passB456', '女', '電影, 音樂'); -- 測試使用者 B
INSERT INTO dbusers (account, nickname, password, gender, interests) VALUES ('userC', '小美', 'passC789', '其他', '旅行, 美食'); -- 測試使用者 C
