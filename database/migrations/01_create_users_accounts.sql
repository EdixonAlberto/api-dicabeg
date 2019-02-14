DROP TABLE "sessions";
DROP TABLE "referrals";
DROP TABLE "users_data";
DROP TABLE "users_accounts";

CREATE TABLE "users_accounts" (
 "user_id" VARCHAR(36) NOT NULL,
 "email" VARCHAR(40) NOT NULL,
 "password" VARCHAR(255) NOT NULL,
 "create_date" TIMESTAMP NULL,
 "update_date" TIMESTAMP NULL,

 PRIMARY KEY (user_id)
);

CREATE INDEX users_accounts_email_UQ ON users_accounts(email);
CREATE INDEX "users_accounts_password_UQ" ON users_accounts("password");
