DROP TABLE "history";
DROP TABLE "videos";
DROP TABLE "sessions";
DROP TABLE "referrals";
DROP TABLE "accounts";
DROP TABLE "codes";
DROP TABLE "users";


-- USUARIOS
CREATE TABLE "users" (
	"user_id" VARCHAR(36) NOT NULL,
	"player_id" VARCHAR(36) NOT NULL,
	"email" VARCHAR(40) NOT NULL,
	"password" VARCHAR(255) NOT NULL,
	"invite_code" VARCHAR(36) NULL DEFAULT NULL, -- acepta null para que pueda funcionar la v1 y v2 juntas
	"registration_code" VARCHAR(36) NULL DEFAULT NULL,
	"username" VARCHAR(20) NOT NULL,
	"names" VARCHAR(20) NULL DEFAULT NULL,
	"lastnames" VARCHAR(20) NULL DEFAULT NULL,
	"age" INTEGER NULL DEFAULT NULL,
	"avatar" VARCHAR NULL DEFAULT NULL,
	"phone" VARCHAR(20) NULL DEFAULT NULL,
	"points" INTEGER DEFAULT 0,
	"money" NUMERIC DEFAULT 0.00,
	"create_date" TIMESTAMP NULL, -- No acepta valores null por defecto
	"update_date" TIMESTAMP NULL,

	CONSTRAINT "users_user_id_PK" PRIMARY KEY ("user_id"),
	CONSTRAINT "users_player_id_UQ" UNIQUE ("player_id"),
	CONSTRAINT "users_email_UQ" UNIQUE ("email"),
	CONSTRAINT "users_invite_code_UQ" UNIQUE ("invite_code"),
	-- CONSTRAINT "users_registration_code_UQ" UNIQUE ("registration_code"), Se debe colocar esta condicion solo si existe un unico codigo por referido
	CONSTRAINT "users_username_UQ" UNIQUE ("username")
);


-- ACCOUNTS
CREATE TABLE "accounts" (
	"user_id" VARCHAR(40) NOT NULL,
	"activated_account" BOOLEAN NOT NULL DEFAULT FALSE,
	"temporal_code" VARCHAR(6) NULL DEFAULT NULL,
	"invite_code" VARCHAR(8) NOT NULL,
	"registration_code" VARCHAR(8) NULL DEFAULT NULL,

	CONSTRAINT "accounts_user_id_PK" PRIMARY KEY ("user_id"),
	CONSTRAINT "account_user_id_FK" FOREIGN KEY("user_id") REFERENCES users("user_id"),
	CONSTRAINT "accounts_invite_code_UQ" UNIQUE ("invite_code")
);


-- REFERIDOS
CREATE TABLE "referrals" (
	"referrals_id" VARCHAR(72) NOT NULL,
	"user_id" VARCHAR(36) NOT NULL,
	"referred_id" VARCHAR(36) NOT NULL,
	"create_date" TIMESTAMP NULL,

	CONSTRAINT "referrals_referrals_id_PK" PRIMARY KEY ("referrals_id"),
	CONSTRAINT "referrals_user_id_FK" FOREIGN KEY("user_id") REFERENCES users("user_id"),
	CONSTRAINT "referrals_referred_id_UQ" UNIQUE ("referred_id")
);


-- SESIONES
CREATE TABLE "sessions" (
	"user_id" VARCHAR(36) NOT NULL,
	"api_token" VARCHAR(255) NULL DEFAULT NULL,
	"expiration_time" TIMESTAMP NULL,
	"create_date" TIMESTAMP NULL,
	"update_date" TIMESTAMP NULL,

	CONSTRAINT "sessions_user_id_PK" PRIMARY KEY ("user_id"),
	CONSTRAINT "sessions_users_id_FK" FOREIGN KEY("user_id") REFERENCES users("user_id"),
	CONSTRAINT "sessions_token_UQ" UNIQUE ("api_token")
);


-- VIDEOS
CREATE TABLE "videos" (
	"video_id" VARCHAR(36) NOT NULL,
	"name" VARCHAR(40) NULL DEFAULT NULL,
	"link" VARCHAR NULL DEFAULT NULL,
	"provider_logo" VARCHAR NULL DEFAULT NULL,
	"question" VARCHAR(255) NULL DEFAULT NULL,
	"correct" INTEGER DEFAULT 0,
	"responses" JSON NULL DEFAULT NULL,
	"total_views" INTEGER DEFAULT 0,
	"create_date" TIMESTAMP NULL,
	"update_date" TIMESTAMP NULL,

	CONSTRAINT "videos_video_id_PK" PRIMARY KEY ("video_id")
);


-- HISTORIAL
CREATE TABLE "history" (
	"history_id" VARCHAR(72) NOT NULL,
	"user_id" VARCHAR(36) NOT NULL,
	"video_id" VARCHAR(36) NOT NULL,
	"total_views" INTEGER DEFAULT 1,
	"update_date" TIMESTAMP NULL,

	CONSTRAINT "history_history_id_PK" PRIMARY KEY ("history_id"),
	CONSTRAINT "history_user_id_FK" FOREIGN KEY ("user_id") REFERENCES users("user_id")
);
