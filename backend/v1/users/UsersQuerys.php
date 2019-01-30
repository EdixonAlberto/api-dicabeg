<?php

require_once '../PgSqlConnection.php';

class UsersQuerys extends PgSqlConnection {

    public static function getAlls() {
        $sql = "SELECT * FROM users";

        $query = self::connection()->prepare($sql);
        $query->execute();

        return $query;
    }

    public static function getById($id, $column = 'id') {
        // var_dump($id); die;
        switch ($column) {
            case 'id':
                $sql = "SELECT * FROM users
                        WHERE user_id = ?";
            break;

            case 'email':
                $sql = "SELECT * FROM users
                        WHERE email = ?";
            break;

            case 'password':
                $sql = "SELECT * FROM users
                        WHERE password = ?";
            break;
        }

        $query = self::connection()->prepare($sql);
        $query->execute([
                $id
            ]);

        return $query;
    }

    public static function insert($arraySet) {
        $sql = "INSERT INTO users (user_id, email, password, create_date, update_date)
                VALUES (?, ?, ?, ?, ?)";

        $query = self::connection()->prepare($sql);
        $query->execute([
                $arraySet[0],
                $arraySet[1],
                $arraySet[2],
                $arraySet[3],
                $arraySet[4]
            ]);

        return $query;
    }

    public static function update($user_id, $columnKey, $columnValue, $timeStamp) {
        if ($columnKey === 'email') {
            $sql = "UPDATE users
                    SET email = ?, update_date = ?
                    WHERE user_id = ?";
        }
        else if ($columnKey === 'password') {
            $sql = "UPDATE users
                    SET password = ?, update_date = ?
                    WHERE user_id = ?";
        }

        $query = self::connection()->prepare($sql);
        $query->execute([
                $columnValue,
                $timeStamp,
                $user_id
            ]);

        return $query;
    }
}
