CREATE TABLE "videos" (
  "video_id" varchar(36),
  "provider_id" varchar(36),
  "name" varchar(20),
  "link" varchar,
  "answers" json,
  "create_date" timestamp,
  PRIMARY KEY (video_id),
  CONSTRAINT videos_provider_id_FK FOREIGN KEY(provider_id) REFERENCES providers(provider_id)
);
