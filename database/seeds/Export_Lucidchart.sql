CREATE TABLE "history_json" (
  "video_id" string,
  "visualizations" integer,
  "update_date" date
);

CREATE TABLE "referrals_json" (
  "user_id" string,
  "create_id" date
);

CREATE TABLE "users_data" (
  "user_id" varchar(36),
  "username" varchar(20),
  "names" varchar(20),
  "lastnames" varchar(20),
  "age" integer,
  "image" varchar,
  "phone" varchar(20),
  "points" integer,
  "movile_data" integer,
  "update_date" timestamp
);

CREATE INDEX "PK, FK" ON  "users_data" ("user_id");

CREATE INDEX "UQ" ON  "users_data" ("username");

CREATE TABLE "providers" (
  "provider_id" varchar(36),
  "name" varchar(20),
  "logo" varchar,
  "link" varchar,
  "create_date" timestamp,
  PRIMARY KEY ("provider_id")
);

CREATE INDEX "UQ" ON  "providers" ("name");

CREATE TABLE "users_referrals" (
  "user_id" varchar(36),
  "referrals" json
);

CREATE INDEX "PK, FK" ON  "users_referrals" ("user_id");

CREATE TABLE "sessions" (
  "user_id" varchar(36),
  "token" varchar(255),
  "update_date" timestamp
);

CREATE INDEX "PK, FK" ON  "sessions" ("user_id");

CREATE TABLE "videos" (
  "video_id" varchar(36),
  "provider_id" varchar(36),
  "name" varchar(20),
  "link" varchar,
  "answers" json,
  "visualizations " integer,
  "create_date" timestamp,
  PRIMARY KEY ("video_id")
);

CREATE INDEX "FK" ON  "videos" ("provider_id");

CREATE TABLE "users_history" (
  "user_id" varchar(36),
  "history" json
);

CREATE INDEX "FK" ON  "users_history" ("user_id");

CREATE TABLE "users_accounts" (
  "user_id" varchar(36),
  "email" varchar(40),
  "password" varchar(255),
  "create_date" timestamp,
  "update_date" timestamp,
  PRIMARY KEY ("user_id")
);

CREATE INDEX "UQ" ON  "users_accounts" ("email");

