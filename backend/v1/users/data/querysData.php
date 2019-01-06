<?php

class querysData extends pgsqlConnection {

    function getAll() {
        $sql = "SELECT * FROM users_data";

        $query = $this->connection()->prepare($sql);
        $query->execute();

        return $query;
    }

    function getBy($id) {
        $sql = "SELECT * FROM users_data
                WHERE data_id = ?";

        $query =$this->connection()->prepare($sql);
        $query->execute([
            $id
        ]);

        return $query;
    }

    function insert($arraySet) {
        $sql = "INSERT INTO users_data (data_id, names, lastnames, age, image, phone, points, referrals)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $query = $this->connection()->prepare($sql);
        $query->execute([
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
?>