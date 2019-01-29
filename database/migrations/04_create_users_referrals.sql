CREATE TABLE "users_referrals" (
  "user_id" varchar(36),
  "referrals" json,
  "create_date" timestamp,
  PRIMARY KEY (user_id),
  CONSTRAINT users_referrals_users_id_FK FOREIGN KEY(user_id) REFERENCES users(user_id)
);
