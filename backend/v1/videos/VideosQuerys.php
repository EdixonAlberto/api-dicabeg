<?php

class VideosQuerys extends PgSqlConnection
{
    public static function selectAlls()
    {
        $sql = "SELECT * FROM Videos";

        $query = self::connection()->prepare($sql);
        $query->execute();

        return $query;
    }

    public static function selectById($value, $key = 'id')
    {
        switch ($key) {
            case 'id':
                $sql = "SELECT * FROM Videos
                        WHERE video_id = ?";
                break;

            case 'email':
                $sql = "SELECT * FROM users_accounts
                        WHERE email = ?";
                break;
        }

        $query = self::connection()->prepare($sql);
        $query->execute([
            $value
        ]);

        return $query;
    }
}
