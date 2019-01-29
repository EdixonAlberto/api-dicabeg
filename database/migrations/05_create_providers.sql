CREATE TABLE "providers" (
  "provider_id" varchar(36),
  "name" varchar(20),
  "logo" varchar,
  "link" varchar,
  "create_date" timestamp,
  PRIMARY KEY (provider_id)
);

CREATE INDEX providers_name_UQ ON providers("name");
