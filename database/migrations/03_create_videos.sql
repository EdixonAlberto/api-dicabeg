DELETE FROM videos;

CREATE TABLE "videos" (
	"video_id" VARCHAR(36) NOT NULL,
	"provider_id" VARCHAR(36) NULL DEFAULT NULL,
	"name" VARCHAR(20) NULL DEFAULT NULL,
	"link" VARCHAR NULL DEFAULT NULL,
	"answers" JSON NULL DEFAULT NULL,
	"visualizations " INTEGER NULL DEFAULT NULL,
	"create_date" TIMESTAMP NULL,
	
	PRIMARY KEY ("video_id")
);
