<?php

class VideosQuerys extends PgSqlConnection
{
    public static function selectAlls()
    {
        $sql = "SELECT * FROM videos";

        $query = self::connection()->prepare($sql);
        $query->execute();

        $rows = $query->rowCount();
        if ($rows > 0) {
            for ($i = 0; $i < $rows; $i++) {
                $objIndexedByColumns = $query->fetch(PDO::FETCH_OBJ);
                $response[] = $objIndexedByColumns;
            }
        } else return false;
        return $response;
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
