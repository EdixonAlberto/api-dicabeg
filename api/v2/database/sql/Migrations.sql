-- USERS
CREATE TABLE "users" (
  "user_id" VARCHAR(36) NOT NULL,
  "activated" BOOLEAN NOT NULL DEFAULT FALSE,
  "username" VARCHAR(20) NOT NULL,
  "email" VARCHAR(40) NOT NULL,
  "balance" NUMERIC DEFAULT 0.00000,
  "names" VARCHAR(20) NULL DEFAULT NULL,
  "lastnames" VARCHAR(20) NULL DEFAULT NULL,
  "age" INTEGER NULL DEFAULT NULL,
  "avatar" VARCHAR NULL DEFAULT NULL,
  "phone" VARCHAR(20) NULL DEFAULT NULL,
  "player_id" VARCHAR(36) NULL DEFAULT NULL,
  "invite_code" VARCHAR(6) NOT NULL,
  "password" VARCHAR(255) NOT NULL,
  "create_date" TIMESTAMP NULL,
  "update_date" TIMESTAMP NULL,
  CONSTRAINT "users_user_id_PK" PRIMARY KEY ("user_id"),
  CONSTRAINT "users_username_UQ" UNIQUE ("username"),
  CONSTRAINT "users_email_UQ" UNIQUE ("email"),
  -- CONSTRAINT "users_registration_code_UQ" UNIQUE ("registration_code"), Se debe colocar esta condicion solo si existe un unico codigo por referido
  CONSTRAINT "users_invite_code_UQ" UNIQUE ("invite_code")
);
-- ACCOUNTS
CREATE TABLE "accounts" (
  "email" VARCHAR(36) NOT NULL,
  "temporal_code" VARCHAR(6) NULL DEFAULT NULL,
  "last_email_sended" VARCHAR(20) NULL DEFAULT NULL,
  "referred_id" VARCHAR(36) NULL DEFAULT NULL,
  "time_zone" VARCHAR NOT NULL,
  "code_create_date" TIMESTAMP NULL,
  CONSTRAINT "accounts_email_PK" PRIMARY KEY ("email"),
  CONSTRAINT "accounts_email_FK" FOREIGN KEY("email") REFERENCES users("email"),
  CONSTRAINT "accounts_referred_id_UQ" UNIQUE ("referred_id")
);
-- TRANSFERS
CREATE TABLE "transfers" (
  "user_id" VARCHAR(36) NOT NULL,
  "transfer_code" VARCHAR(6) NOT NULL,
  "concept" VARCHAR(40) NULL DEFAULT NULL,
  "responsible" VARCHAR(36) NOT NULL,
  "amount" NUMERIC DEFAULT 0.00000,
  "previous_balance" NUMERIC DEFAULT 0.00000,
  "current_balance" NUMERIC DEFAULT 0.00000,
  "create_date" TIMESTAMP NULL,
  CONSTRAINT "transfers_user_id_FK" FOREIGN KEY("user_id") REFERENCES users("user_id")
);
-- COMMISSIONS
CREATE TABLE "commissions" (
  "user_id" VARCHAR(36) NOT NULL,
  "amount" NUMERIC DEFAULT 0.00000,
  "commission" NUMERIC DEFAULT 0.00000,
  "create_date" TIMESTAMP NULL,
  CONSTRAINT "commissions_user_id_UQ" UNIQUE ("user_id")
);
-- REFERREDS
CREATE TABLE "referreds" (
  "user_id" VARCHAR(36) NOT NULL,
  "referred_id" VARCHAR(36) NOT NULL,
  "create_date" TIMESTAMP NULL,
  CONSTRAINT "referreds_user_id_FK" FOREIGN KEY("user_id") REFERENCES users("user_id"),
  CONSTRAINT "referreds_referred_id_FK" FOREIGN KEY("referred_id") REFERENCES users("user_id")
);
-- VIDEOS
CREATE TABLE "videos" (
  "video_id" VARCHAR(36) NOT NULL,
  "name" VARCHAR(40) NULL DEFAULT NULL,
  "link" VARCHAR NULL DEFAULT NULL,
  "size" INTEGER DEFAULT 0,
  "provider_logo" VARCHAR NULL DEFAULT NULL,
  "question" VARCHAR(255) NULL DEFAULT NULL,
  "correct" INTEGER DEFAULT 0,
  "responses" JSON NULL DEFAULT NULL,
  "total_views" INTEGER DEFAULT 0,
  "create_date" TIMESTAMP NULL,
  "update_date" TIMESTAMP NULL,
  CONSTRAINT "videos_video_id_PK" PRIMARY KEY ("video_id")
);
-- HISTORY
CREATE TABLE "history" (
  "user_id" VARCHAR(36) NOT NULL,
  "video" VARCHAR(36) NOT NULL,
  "total_views" INTEGER DEFAULT 0,
  "create_date" TIMESTAMP NULL,
  CONSTRAINT "history_user_id_FK" FOREIGN KEY ("user_id") REFERENCES users("user_id"),
  CONSTRAINT "history_video_FK" FOREIGN KEY ("video") REFERENCES videos("video_id")
);
-- OPTIONS
CREATE TABLE "options" ("expiration_time" VARCHAR NOT NULL);