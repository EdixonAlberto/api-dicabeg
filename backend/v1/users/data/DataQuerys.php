<?php

class DataQuerys extends PgSqlConnection
{
    public static function selectAlls()
    {
        $sql = "SELECT * FROM users_data";

        $query = self::connection()->prepare($sql);
        $query->execute();

        return $query;
    }

    public static function selectById()
    {
        $sql = "SELECT * FROM users_data
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);

        return $query;
    }

    public static function insert($arraySet)
    {
        $sql = "INSERT INTO users_data (user_id, username, names, lastnames, age, image, phone, points, movile_data)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id'],
            $arraySet[0],
            $arraySet[1],
            $arraySet[2],
            $arraySet[3],
            $arraySet[4],
            $arraySet[5],
            $arraySet[6],
            $arraySet[7]
        ]);

        return $query;
    }

    public static function update($arraySet)
    {
        $user_id = $_GET['id'];
        $sql = "UPDATE users_data
                SET username = ?, names = ?, lastnames = ?, age = ?, image = ?, phone = ?, points = ?, movile_data = ?
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $arraySet[0],
            $arraySet[1],
            $arraySet[2],
            $arraySet[3],
            $arraySet[4],
            $arraySet[5],
            $arraySet[6],
            $arraySet[7],
            $_GET['id']
        ]);

        return $query;
    }

    public static function delete()
    {
        $sql = "DELETE FROM users_data
                WHERE user_id = ?";

        $query = self::connection()->prepare($sql);
        $query->execute([
            $_GET['id']
        ]);

        return $query;
    }
}
