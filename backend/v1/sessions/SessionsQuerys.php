<?php

class SessionsQuerys extends PgSqlConnection
{
    public static function selectAlls()
    {
        $sql = "SELECT * FROM sessions";

        $query = self::connection()->prepare($sql);
        $query->execute();

        return GeneralMethods::processSelect($query);
    }

    public static function selectById()
    {
        $sql = "SELECT * FROM sessions
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);

        return GeneralMethods::processSelect($query);
    }

    public static function select($where, $value, $fields = '*')
    {
        $sql = "SELECT " . $fields
            . " FROM sessions
                WHERE " . $where . " = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $value
        ]);

        return GeneralMethods::processSelect($query);
    }

    public static function insert($token)
    {
        $sql = "INSERT INTO sessions (user_id, token, create_date)
                VALUES (?, ?, ?)";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id'],
            $token,
            date('Y-m-d h:i:s')
        ]);

        return GeneralMethods::processQuery($query);
    }

    public static function delete()
    {
        $sql = "DELETE FROM sessions
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);

        return GeneralMethods::processQuery($query);
    }
}
