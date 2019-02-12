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

    public static function selectById()
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

    public static function insert($token)
    {
        $sql = "INSERT INTO sessions (user_id, token, create_date)
                VALUES (?, ?, ?)";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id'],
            $token,
            date('Y-d-m')
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
