CREATE TABLE "sessions" (
  "user_id" varchar(36),
  "token" varchar(255),
  "update_date" timestamp,

  PRIMARY KEY (user_id),
  CONSTRAINT users_session_users_id_FK FOREIGN KEY(user_id) REFERENCES users_accounts(user_id)
);
