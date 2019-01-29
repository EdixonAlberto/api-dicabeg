CREATE TABLE "users_session" (
  "user_id" varchar(36),
  "token_session" varchar,
  "token_email" varchar,
  "update_date" timestamp,
  PRIMARY KEY (user_id),
  CONSTRAINT users_session_users_id_FK FOREIGN KEY(user_id) REFERENCES users(user_id)
);
