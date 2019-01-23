<?php
include 'querysAccount.php';
include 'users.php';
include 'data/querysData.php';
include '../generateGui.php';

class accounts extends querysAccount {

    static function getAccountsAlls() {
        $query = self::getAll();
        $arrayResponse = users::getUsersAlls($query);
        return self::response($arrayResponse);
    }

    static function getAccountById($id) {
        $query = self::getBy($id);
        $arrayResponse = users::getUsersAlls($query);
        return self::response($arrayResponse);
    }

    function signUp($POST) {
        $existAccount = $this->checkoutAccount($POST);

        if (!$existAccount) {
            $gui = new generateGui();
            $arrayAccountNew[] = trim($gui->code, '{}');

            foreach ($POST as $value) {
                $arrayAccountNew[] = $value;
            }
            $result = $this->insertAccount($arrayAccountNew);
            $accountOperation = $this->interpretResult($result);

            if ($accountOperation === true) {
                $arraySetNull[] = $arrayAccountNew[0];
                for ($i = 0; $i < 7; $i++) {
                    $arraySetNull[] = null;
                }
                $queryData = new querysData();
                $result = $queryData->insert($arraySetNull);
                $dataOperation = $this->interpretResult($result);

                if ($dataOperation === true) {
                    $arrayResponse[] = [
                        'account_id' => $arrayAccountNew[0],
                        'Username' => $arrayAccountNew[1],
                        'Email' => $arrayAccountNew[2],
                        'Password' => $arrayAccountNew[3]
                    ];
                    http_response_code(200);
                }
                else {
                    http_response_code(400);
                    $arrayResponse[] = ['Error' => $dataOperation];
                }
            }
            else {
                http_response_code(400);
                $arrayResponse[] = ['Error' => $accountOperation];
            }
        }
        else {
            http_response_code(400);
            $arrayResponse[] = ['Error' => 'Account Exist'];
        }

        return $this->response($arrayResponse);
    }

    function signIn($PUT) {
        $existAccount = $this->checkoutAccount($PUT);

        if ($existAccount) {
            array_shift($PUT); // se borra el primer valor del array: email
            foreach ($PUT as $key => $value) {
                $result = $this->getBy($value, $key);
                $rows = $result->rowCount();
            }
            if ($rows) {
                http_response_code(200);
                $arrayResponse[] = ['Response 200' => 'Verified Account'];
            }
            else {
                http_response_code(400);
                $arrayResponse[] = ['Error' => 'Password Incorrect'];
            }
        }
        else {
            http_response_code(400);
            $arrayResponse[] = ['Error' => 'Account Not Exist'];
        }

        return $this->response($arrayResponse);
    }

    private function interpretResult($result) {
        $error = $result->errorInfo();
        $errorExist = !is_null($error[1]);

        return $errorExist ? $error[2] : true;
    }

    private function checkoutAccount($PUT) {
        foreach ($PUT as $key => $value) {
            $result = $this->getBy($value, $key);
            $rows = $result->rowCount();

            return $rows ? true : false;
        }
    }

    private function response($_arrayResponse) {
        $arrayResponse['userAccount'] = $_arrayResponse;

        return json_encode($arrayResponse);
    }
}
?>