DELETE FROM "history";
DELETE FROM "videos";
DELETE FROM "options";

INSERT INTO "videos" ("video_id", "name", "link", "provider_logo", "question", "correct", "responses", "total_views", "create_date", "update_date")
VALUES (
   'd415fdbe-b1bb-4d09-a8e7-154468abfc63',
   'El Conejo',
   'http://clips.vorwaerts-gmbh.de/VfE_html5.mp4',
   'path',
   'cual es el genero',
   2,
   '["ciencia","deporte","animación","cultura","política","tecnología","naturaleza","acción","gastronomía","curso","tutorial"]',
   0,
   '2019-02-18 13:00:00',
   NULL
);

INSERT INTO "videos" ("video_id", "name", "link", "provider_logo", "question", "correct", "responses", "total_views", "create_date", "update_date")
VALUES (
   '2adbae63-c5a1-4572-8839-a06f985b03da',
   'Cascada-Japon',
   'https://tekeye.uk/html/images/Joren_Falls_Izu_Japan.mp4',
   'path',
   'cual es el genero',
   6,
   '["ciencia","deporte","animación","cultura","política","tecnología","naturaleza","acción","gastronomía","curso","tutorial"]',
   0,
   '2019-02-18 14:00:00',
   NULL
);

INSERT INTO "videos" ("video_id", "name", "link", "provider_logo", "question", "correct", "responses", "total_views", "create_date", "update_date")
VALUES (
   'e737a0fd-1793-4016-84ff-37f0b8a9035a',
   'iPhone-XR',
   'https://app.coverr.co/s3/mp4/Scrolling-iPhone-XR.mp4',
   'path',
   'cual es el genero',
   5,
   '["ciencia","deporte","animación","cultura","política","tecnología","naturaleza","acción","gastronomía","curso","tutorial"]',
   0,
   '2019-02-18 15:00:00',
   NULL
);

INSERT INTO "options" ("expiration_time")
VALUES ("30 min");
