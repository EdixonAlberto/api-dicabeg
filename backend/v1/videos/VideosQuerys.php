<?php

class VideosQuerys extends PgSqlConnection
{
    public static function selectAlls()
    {
        $sql = "SELECT * FROM videos";

        $query = self::connection()->prepare($sql);
        $query->execute();
        return GeneralMethods::processSelect($query);
    }

    public static function selectById()
    {
        $sql = "SELECT *
                FROM videos
                WHERE video_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);
        return GeneralMethods::processSelect($query);
    }
}
