<?php

class data extends querysData {

    function getdataAlls() {
        $query = $this->getAll();
        $Rows = $query->rowCount();

        if ($Rows) {
            for ($i = 0; $i < $Rows ; $i++) {
                $arrayIndexedByColumns['usersData'][] = $query->fetch(PDO::FETCH_ASSOC);
            }

            $jsonData = json_encode($arrayIndexedByColumns);
            return $jsonData;
        }
        else {
            return $this->message("don't exist elements");
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
            return $this->message("don't exist elements");
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
            echo 'next' . '<br />';
        }

        // $this->update($_arraydataNew);

        var_dump($_arraydataNew);
        die();

        // if ($idExist) {
        //     $_names = is_null($arraySet[0]) ? $jsonData['names'] : $names;
        //     $_lastnames = is_null($lastnames) ? $jsonData['lastnames'] : $lastnames;
        //     $_age = is_null($age) ? $jsonData['age'] : $age;
        //     $_image = is_null($image) ? $jsonData['image'] : $image;
        //     $_phone = is_null($phone) ? $jsonData['phone'] : $phone;
        //     $_points = is_null($points) ? $jsonData['points'] : $points;
        //     $_referrals = is_null($referrals) ? $jsonData['referrals'] : $referrals;

        // }
        // else die('error');


        //$this->update($_dataNew);


    }


    function message($message) {
        echo json_encode(['message' => $message]);
    }

    private function checkoutEmail($email) {
        $userData = $this->getBy($email);

        $existRow = $userData->rowCount() ? true : false;
        return $existRow;
    }
}
?>