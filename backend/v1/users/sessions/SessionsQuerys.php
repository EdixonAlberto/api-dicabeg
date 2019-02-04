<?php

class SessionsQuerys extends PgSqlConnection
{
    public static function selectAlls()
    {
        $sql = "SELECT * FROM sessions";

        $query = self::connection()->prepare($sql);
        $query->execute();

        return $query;
    }

    public static function selectById($value)
    {
        // TODO: Pensar mejor si esta consulta se reduce solo a select id
        $sql = "SELECT * FROM sessions
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);

        return $query;
    }

    public static function insert($value)
    {
        $sql = "INSERT INTO sessions (user_id, token)
                VALUES (?, ?)";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id'],
            $value
        ]);

        return $query;
    }

    public static function delete()
    {
        $sql = "DELETE FROM sessions
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);

        return $query;
    }
}
