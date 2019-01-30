<?php

require_once '../../GeneralQuerys.php';
require_once 'DataQuerys.php';

class Data extends DataQuerys {

    public static function getDataAlls() {
        $query = self::getAlls();
        $arrayResponse = GeneralQuerys::getAlls($query);

        return self::response($arrayResponse);
    }

    function getDataById($id) {
        $query = self::getById($id);
        $arrayResponse = GeneralQuerys::getById($query);

        return self::response($arrayResponse);
    }






    function updatedataById($arraydataNew) {
        $arraydataOld = $this->getdataById($arraydataNew[0]);
        $_arraydataNew = [];

        var_dump($arraydataNew);
        var_dump($arraydataOld);
        die();

        $i = 0;
        foreach ($arraydataOld['user_data'] as $value) {
            $_arraydataNew[] = is_null($arraydataNew[$i]) ? $value : $arraydataNew[$i];
            $i++;
        }

        // $this->update($_arraydataNew);

        var_dump($_arraydataNew);
        die();
    }

    // private function checkoutEmail($email) {
    //     $userData = $this->getBy($email);

    //     $existRow = $userData->rowCount() ? true : false;
    //     return $existRow;
    // }

    function response($_arrayResponse) {
        $arrayResponse['usersData'] = $_arrayResponse;

        return json_encode($arrayResponse);
    }
}
