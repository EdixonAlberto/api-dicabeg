CREATE TABLE "videos_history" (
  "history_id" varchar(36),
  "user_id" varchar(36),
  "video_id" varchar(36),
  "visualizations" integer,
  "update_date" timestamp,
  PRIMARY KEY ("history_id"),
  CONSTRAINT videos_history_user_id_FK FOREIGN KEY(user_id) REFERENCES users(user_id),
  CONSTRAINT videos_history_video_id_FK FOREIGN KEY(video_id) REFERENCES videos(video_id)
);
