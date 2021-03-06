-- Vista para mostrar la clasificacion de usuarios
CREATE view view_ranking AS
SELECT username, email, balance
FROM users ORDER BY balance DESC;

-- Vista para mostrar el total de dicag generados
CREATE view view_total_balance AS
SELECT SUM(balance) AS balance
FROM users;

-- Vista para mostrar los usuarios con rol de empresa
CREATE view view_enterprises AS
SELECT *
FROM users
WHERE rol_id = 2