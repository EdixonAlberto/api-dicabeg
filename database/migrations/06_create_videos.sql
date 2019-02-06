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