<?php

class account {

    function __construct() {

    }

    function insertAccount($username, $email, $password) {
        $correctOperation = insertData();
        if ($correctOperation == 'ok') {

        }
        else {
            $responseRest = new responseRest();
            $result = $responseRest->error($correctOperation[2]);
            echo $result;
        }
    }

    function insertData() {
        $guidV4 = trim(com_create_guid(), '{}');
        $arrayDefaultData[] = $guidV4;
        include '../../arraySet.php';

        foreach ($arraySet as $set) {
            $arrayDefaultData[] = null;
        }
        $query = new querysData();
        $result = $query->insert($arrayDefaultData);

        return $result->errorInfo();
    }

    function signUp($username, $email, $pass) {
        $emailExist = $this->checkoutEmail($email);

        if ($emailExist) {
            return $this->message('email exist');
        }
        else {
            $result = $this->insertAccount($username, $email, $pass);

            $errorExist = !is_null($result[1]);
            if ($errorExist) {
                return $this->message('(error) could not register');
            }
            else {
                return $this->message('user signuped');
            }
        }
}
}
?>