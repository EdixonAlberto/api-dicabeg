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
            return 'account exist';
        }
        else {
            $_arrayAccountNew[] = trim(com_create_guid(), '{}');
            foreach ($arrayAccountNew as $value) {
                $_arrayAccountNew[] = $value;
            }
            $result = $this->insertAccount($_arrayAccountNew);
            $interpret1 = $this->interpretResult($result);

            if ($interpret1) {
                $arraySetNull[] = $_arrayAccountNew[0];
                for ($i = 0; $i < 7; $i++) {
                    $arraySetNull[] = null;
                }
                $queryData = new querysData();
                $result = $queryData->insert($arraySetNull);
                $interpret2 = $this->interpretResult($result);

                return $interpret2 ? 'added account' : $interpret2;
            }
            else {
                return $interpret1;
            }
        }
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

            return $rows ? true : false;
        }
    }
}
?>