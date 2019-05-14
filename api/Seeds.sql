DELETE FROM "videos";


-- USERS
-- INSERT INTO "users" ("user_id", "email", "password", "authentication", "invite_code", "registration_code", "username", "names", "lastnames", "age", "avatar", "phone", "points", "money", "create_date", "update_date")
-- VALUES ('D08FEB0F-64DF-4696-99F7-4480A4CB8125', 'edixonalbertto@gmail.com', '$2y$10$Ll/Zso6Nn9nMx/p5s9GJ1Oeji08q2R90zT2uqyKiMlNJwF2voi10u', FALSE, '38A6407C-62EE-4D7C-81DC-BCE7C4347814', NULL, 'edixonalbertto', 'Edixon', 'Piña', 27, 'path', '+584263070365', 0, 0.00, '2019-02-18 13:00:00', NULL);

-- INSERT INTO "users" ("user_id", "email", "password", "authentication", "invite_code", "registration_code", "username", "names", "lastnames", "age", "avatar", "phone", "points", "money", "create_date", "update_date")
-- VALUES ('DA39FEED-B061-4EED-B862-2A40B884D2B4', 'romulocg25@gmail.com', '$2y$10$Ll/Zso6Nn9nMx/p5s9GJ1Oeji08q2R90zT2uqyKiMlNJwF2voi10u', FALSE, '824CC464-90BF-4A59-B439-360C42E23961', '38A6407C-62EE-4D7C-81DC-BCE7C4347814', 'romulocg25', 'Romulo', 'Corona', 12, 'path','+584245765881', 0, 0.00, '2019-02-18 14:00:00', NULL);

-- INSERT INTO "users" ("user_id", "email", "password", "authentication", "invite_code", "registration_code", "username", "names", "lastnames", "age", "avatar", "phone", "points", "money", "create_date", "update_date")
-- VALUES ('C7483ED4-2E67-4018-8D12-910B00139A23', 'carlosyaguaz@hotmail.com', '$2y$10$Ll/Zso6Nn9nMx/p5s9GJ1Oeji08q2R90zT2uqyKiMlNJwF2voi10u', FALSE, 'A59A9BB4-781A-49F1-BFB6-D9DAABBF6205', '38A6407C-62EE-4D7C-81DC-BCE7C4347814', 'carlosyaguaz', 'Carlos', 'Yaguaz', 40, 'path', '+584261500365', 0, 0.00, '2019-02-18 15:00:00', NULL);


-- -- REFERRALS
-- INSERT INTO "referrals" ("referrals_id", "user_id", "referred_id","create_date")
-- VALUES ('D08FEB0F-64DF-4696-99F7-4480A4CB8125DA39FEED-B061-4EED-B862-2A40B884D2B4', 'D08FEB0F-64DF-4696-99F7-4480A4CB8125', 'DA39FEED-B061-4EED-B862-2A40B884D2B4', '2019-02-18 14:00:00');

-- INSERT INTO "referrals" ("referrals_id", "user_id", "referred_id","create_date")
-- VALUES ('D08FEB0F-64DF-4696-99F7-4480A4CB8125C7483ED4-2E67-4018-8D12-910B00139A23', 'D08FEB0F-64DF-4696-99F7-4480A4CB8125', 'C7483ED4-2E67-4018-8D12-910B00139A23', '2019-02-18 15:00:00');


-- SESSIONS
-- INSERT INTO "sessions" ("user_id", "api_token", "create_date")
-- VALUES('D08FEB0F-64DF-4696-99F7-4480A4CB8125', '$10$TWaYzM.VXFpKv13VL2MNqOzr2CNbGDs0yMaD0dkBvO0Z70q0EiNyO', '2019-02-19 13:10:00');


-- VIDEOS
INSERT INTO "videos" ("video_id", "name", "link", "provider_logo", "question", "correct", "responses", "total_views", "create_date", "update_date")
VALUES (
   'd415fdbe-b1bb-4d09-a8e7-154468abfc63',
   'El Conejo',
   'http://clips.vorwaerts-gmbh.de/VfE_html5.mp4',
   'path',
   'cual es el genero',
   2,
   '["ciencia","deporte","animacion","cultura","política","tecnología","naturaleza","acción","gastronomía","curso","tutorial"]',
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
   '["ciencia","deporte","animacion","cultura","política","tecnología","naturaleza","acción","gastronomía","curso","tutorial"]',
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
   '["ciencia","deporte","animacion","cultura","política","tecnología","naturaleza","acción","gastronomía","curso","tutorial"]',
   0,
   '2019-02-18 15:00:00',
   NULL
);


-- HISTORY
-- INSERT INTO "history" ("history_id", "user_id", "video_id", "history_views", "update_date")
-- VALUES ('D08FEB0F-64DF-4696-99F7-4480A4CB812586619003-ECB3-4E50-A3D9-881EF2A19317', 'D08FEB0F-64DF-4696-99F7-4480A4CB8125', '86619003-ECB3-4E50-A3D9-881EF2A19317', 1, '2019-02-18 14:00:00');

-- INSERT INTO "history" ("history_id", "user_id", "video_id", "history_views", "update_date")
-- VALUES ('D08FEB0F-64DF-4696-99F7-4480A4CB812521EF6574-A063-4E22-88AF-6F169153BAF4', 'D08FEB0F-64DF-4696-99F7-4480A4CB8125', '21EF6574-A063-4E22-88AF-6F169153BAF4', 1, '2019-02-18 15:00:00');
