DROP TABLE "sessions";
CREATE TABLE "sessions" (
  "user_id" varchar(36),
  "token" varchar(255) NOT NULL,
  "create_date" date,

  PRIMARY KEY (user_id),
  CONSTRAINT users_session_users_id_FK FOREIGN KEY(user_id) REFERENCES users_accounts(user_id)
);

CREATE INDEX "sessions_token_UQ" ON sessions("token");
