<?php

class DataQuerys extends PgSqlConnection {

    public static function getAlls() {
        $sql = "SELECT * FROM users_data";

        $query = self::connection()->prepare($sql);
        $query->execute();

        return $query;
    }

    public static function getById($id) {
        $sql = "SELECT * FROM users_data
                WHERE user_id = ?";

        $query =self::connection()->prepare($sql);
        $query->execute([
            $id
        ]);

        return $query;
    }

    public static function insert($arraySet) {
        $sql = "INSERT INTO users_data (user_id, username, names, lastnames, age, image, phone, points, movile_data, update_date)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

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
                $arraySet[8],
                $arraySet[9]
            ]);

        return $query;
    }

    function update($arraySet) {
        $sql = "UPDATE users_data
                SET names = ?, lastnames = ?, age = ?, image = ?, phone = ?, points = ?, referrals = ?
                WHERE data_id = ?";

        $query = $this->connection()->prepare($sql);
        $query->execute([
                $arraySet[1],
                $arraySet[2],
                $arraySet[3],
                $arraySet[4],
                $arraySet[5],
                $arraySet[6],
                $arraySet[7],
                $arraySet[0]
            ]);

        return $query;
    }


    // no deberia existir este metodo ?
    function delete($id) {
        $sql = "DELETE FROM users_data
                WHERE data_id = ?";

        $query = $this->connection()->prepare($sql);
        $query->execute([
                $id
            ]);

        return $query;
    }
}
