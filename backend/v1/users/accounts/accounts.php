<?php

class accounts extends querysAccount {

    function getAccountsAlls() {
        $query = $this->getAll();
        $rows = $query->rowCount();

        if ($rows) {
            for ($i = 0; $i < $rows; $i++) {
                $arrayIndexedByColumns['userAccounts'][] = $query->fetch(PDO::FETCH_ASSOC);
            }

            return json_encode($arrayIndexedByColumns);
        }
    }

    function getAccountById($id) {
        $userAccount = $this->getBy($id);
        $existRow = $userAccount->rowCount();

        if ($existRow) {
            $arrayIndexedByColumns = $userAccount->fetch(PDO::FETCH_ASSOC);
            $arrayAccount['userAccount'] = $arrayIndexedByColumns;

            return json_encode($arrayAccount);
        }
        else {
            return 'account not exist';
        }
    }

    function signUp($arrayAccountNew) {

        $existAccount = $this->checkoutAccount($arrayAccountNew);

        if ($existAccount) {
            $arrayResult = ['operation' => 'Account Exist'];
        }
        else {
            $_arrayAccountNew[] = trim(com_create_guid(), '{}');

            foreach ($arrayAccountNew as $value) {
                $_arrayAccountNew[] = $value;
            }
            $result = $this->insertAccount($_arrayAccountNew);
            $operationAccount = $this->interpretResult($result);

            if ($operationAccount === true) {
                $arraySetNull[] = $_arrayAccountNew[0];
                for ($i = 0; $i < 7; $i++) {
                    $arraySetNull[] = null;
                }
                $queryData = new querysData();
                $result = $queryData->insert($arraySetNull);
                $operationData = $this->interpretResult($result);

                if ($operationData === true) {
                    $arrayResponse['userAccount'][] = ['response' => 'Added Account'];
                    $arrayResult = [
                        'account_id' => $_arrayAccountNew[0],
                        'username' => $_arrayAccountNew[1],
                        'email' => $_arrayAccountNew[2],
                        'password' => $_arrayAccountNew[3]
                    ];
                }
                else $arrayResult = ['response' => $operationData];
            }
            else $arrayResult = ['response' => $operationAccount];
        }
        $arrayResponse['userAccount'][] = $arrayResult;
        return json_encode($arrayResponse);
    }

    function signIn($PUT) {
        $existAccount = $this->checkoutAccount($PUT);

        if (!$existAccount) {
            return 'Account Not Exist';
        }
        else {
            array_shift($PUT); // se borra el primer valor del array: email
            foreach ($PUT as $key => $value) {
                $result = $this->getBy($value, $key);
                $rows = $result->rowCount();

                return $rows ? 'Verified Account' : 'Password Incorrect';
            }
        }
    }

    function interpretResult($result) {
        $error = $result->errorInfo();
        $errorExist = !is_null($error[1]);

        return $errorExist ? $error[2] : true;
    }

    function checkoutAccount($PUT) {
        foreach ($PUT as $key => $value) {
            $result = $this->getBy($value, $key);
            $rows = $result->rowCount();

            return $rows;
        }
    }
}
?>