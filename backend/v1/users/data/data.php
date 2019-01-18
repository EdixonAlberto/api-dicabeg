<?php
include 'querysData.php';
include '../users.php';


class data extends querysData {
    private $users;

    function __construct() {
        $this->users = new users();
    }
    function getDataAlls() {
        $query = $this->getAll();
        $arrayResponse = $this->users->getUsersAlls($query);

        return $this->response($arrayResponse);
    }

    function getdataById($id) {
        $query = $this->getBy($id);
        $arrayResponse = $this->users->getUsersById($query);

        return $this->response($arrayResponse);
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
?>