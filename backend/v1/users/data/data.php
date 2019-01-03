<?php

class data extends querysData {

    function getdataAlls() {
        $query = $this->getAll();
        $rows = $query->rowCount();

        if ($rows) {
            for ($i = 0; $i < $rows; $i++) {
                $arrayIndexedByColumns['usersData'][] = $query->fetch(PDO::FETCH_ASSOC);
            }

            $jsonData = json_encode($arrayIndexedByColumns);
            return $jsonData;
        }
        else {
            return "don't exist elements";
        }
    }

    function getdataById($id) {
        $userData = $this->getBy($id);
        $existRow = $userData->rowCount();

        if ($existRow) {
            $arrayIndexedByColumns = $userData->fetch(PDO::FETCH_ASSOC);
            $arrayData['userData'][] = $arrayIndexedByColumns;

            return json_encode($arrayData);
        }
        else {
            return "data not exist";
        }
    }

    function updatedataById($arraydataNew) {
        $arraydataOld = $this->getdataById($arraydataNew[0]);
        $_arraydataNew = [];

        var_dump($arraydataNew);
        var_dump($arraydataOld);
        // die();

        $i = 0;
        foreach ($arraydataOld['user_data'] as $value) {
            $_arraydataNew[] = is_null($arraydataNew[$i]) ? $value : $arraydataNew[$i];
            $i++;
        }

        // $this->update($_arraydataNew);

        var_dump($_arraydataNew);
        die();
    }

    private function checkoutEmail($email) {
        $userData = $this->getBy($email);

        $existRow = $userData->rowCount() ? true : false;
        return $existRow;
    }
}
?>