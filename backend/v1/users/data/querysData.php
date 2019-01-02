<?php

class querysData {
    private $dataPostgre;

    public function __construct() {
        $this->dataPostgre = new dataBase('pgsql', 'DATABASE_URL_LOCAL');
    }

    function getAll() {
        $sql = "SELECT * FROM users_data";

        $query = $this->dataPostgre->connect()->prepare($sql);
        $query->execute();

        return $query;
    }

    function getBy($id) {
        $sql = "SELECT * FROM users_data
                WHERE id = ?";

        $query = $this->dataPostgre->connect()->prepare($sql);
        $query->execute([
            $id
        ]);

        return $query;
    }

    function insert($arraySet) {
        $sql = "INSERT INTO users_data (id, names, lastnames, age, image, phone, points, referrals)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $query = $this->dataPostgre->connect()->prepare($sql);
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
                WHERE id = ?";

        $query = $this->dataPostgre->connect()->prepare($sql);
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
                WHERE id = ?";

        $query = $this->dataPostgre->connect()->prepare($sql);
        $query->execute([
                $id
            ]);

        return $query;
    }
}
?>