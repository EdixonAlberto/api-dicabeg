CREATE TABLE "users" (
 "user_id" VARCHAR(36),
 "email" VARCHAR(40),
 "password" VARCHAR(255),
 "create_date" TIMESTAMP,
 "update_date" TIMESTAMP,
 PRIMARY KEY (user_id)
);

CREATE INDEX users_email_UQ ON users(email);
