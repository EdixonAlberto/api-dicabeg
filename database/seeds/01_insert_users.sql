DELETE FROM "sessions";
DELETE FROM "users_data";
DELETE FROM "users_accounts";

INSERT INTO "users_accounts" ("user_id", "email", "password") VALUES ('D08FEB0F-64DF-4696-99F7-4480A4CB8125', 'edixonalbertto@gmail.com', '$2y$10$Ll/Zso6Nn9nMx/p5s9GJ1Oeji08q2R90zT2uqyKiMlNJwF2voi10u');
INSERT INTO "users_accounts" ("user_id", "email", "password") VALUES ('C7483ED4-2E67-4018-8D12-910B00139A23', 'carlosyaguaz@gmail.com', '$2y$10$WbsX.shykrIwsjRJJHkns.S2gUofr28cZuDMhpDJuFGGFYkEw8V9q');
INSERT INTO "users_accounts" ("user_id", "email", "password") VALUES ('DA39FEED-B061-4EED-B862-2A40B884D2B4', 'romulocg25@gmail.com', '$2y$10$crc9HN7luU7g6BXs25IO2epGT4FbIf4bOM7MkCJKFCmbpYk2IfTqe');
INSERT INTO "users_accounts" ("user_id", "email", "password") VALUES ('B588786E-6F70-4237-80E8-832173996120', 'oscar2019@hotmail.com', '$2y$10$C7AQEbbVFJfqnNA9sq04P.IzGoXeLi7OUtkVE4ZwO1yt2wNmfrCwm');
INSERT INTO "users_accounts" ("user_id", "email", "password") VALUES ('1192225D-A595-416E-B232-EF84C2A52BEB', 'pepe@outlook.com', '$2y$10$Q9P9KVnaBUABUgHMCWE5f.YBDAbCfPLJZrNK4ps0Xycir8nE7cbIS');

INSERT INTO "users_data" ("user_id", "username", "names", "lastnames", "age", "image", "phone", "points", "movile_data") VALUES ('D08FEB0F-64DF-4696-99F7-4480A4CB8125', 'EdixonAlberto', 'Edixon', 'Piña', 27, 'https://i2.wp.com/multianime.com.mx/wp-content/uploads/2019/01/browsette-nintendo-descartada-oficial.jpg?resize=560%2C600&ssl=1', '+584263070365', 100, 0);
INSERT INTO "users_data" ("user_id", "username", "names", "lastnames", "age", "image", "phone", "points", "movile_data") VALUES ('C7483ED4-2E67-4018-8D12-910B00139A23', 'carlosyaguaz', 'Carlos', 'Yaguaz', 30, NULL, '+584121524541', 10, 100);
INSERT INTO "users_data" ("user_id", "username", "names", "lastnames", "age", "image", "phone", "points", "movile_data") VALUES ('DA39FEED-B061-4EED-B862-2A40B884D2B4', 'romulocg25', 'Romulo', 'Corona', NULL, NULL,'+584245765881', 2, 0);
INSERT INTO "users_data" ("user_id", "username", "names", "lastnames", "age", "image", "phone", "points", "movile_data") VALUES ('B588786E-6F70-4237-80E8-832173996120', 'oscar2019', 'Oscar', 'Pulido', 40, NULL, '+584261500365', 0, 1);
INSERT INTO "users_data" ("user_id", "username", "names", "lastnames", "age", "image", "phone", "points", "movile_data") VALUES ('1192225D-A595-416E-B232-EF84C2A52BEB', 'pepe', 'pepe', 'Ramirez', 40, NULL, '+584145685050', 1, 500);