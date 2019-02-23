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

    public static function selectById($getError)
    {
        $sql = "SELECT * FROM sessions
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);
        return GeneralMethods::processSelect($query, $getError);
    }

    public static function selectByToken()
    {
        $sql = "SELECT * FROM sessions
                WHERE api_token = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_SERVER['HTTP_API_TOKEN']
        ]);
        return GeneralMethods::processSelect($query, false);
    }

    public static function insert($token)
    {
        $sql = "INSERT INTO sessions (user_id, api_token, create_date)
                VALUES (?, ?, ?)";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id'],
            $token,
            date('Y-m-d H:i')
        ]);
        return GeneralMethods::processQuery($query);
    }

    public static function update($token)
    {
        $sql = "UPDATE sessions
                SET api_token = ?, update_date = ?
                WHERE api_token = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $token,
            date('Y-m-d H:i'),
            $_SERVER['HTTP_API_TOKEN']
        ]);
        return GeneralMethods::processQuery($query);
    }

    public static function delete()
    {
        $sql = "DELETE FROM sessions
                WHERE api_token = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_SERVER['HTTP_API_TOKEN']
        ]);
        return GeneralMethods::processDelete($query);
    }
}
