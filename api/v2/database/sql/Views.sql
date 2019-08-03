Create view view_ranking AS
SELECT
  username,
  email,
  balance
FROM
  users
ORDER BY
  balance DESC;