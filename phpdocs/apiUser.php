<?php
    include 'querys.php';

    class apiUser {
        function signUp($email, $pass) {
            $emailExist = $this->checkoutEmail($email);

            if ($emailExist) {
                return $this->message('email exist');
            }
            else {
                $query = new querys();
                $result = $query->insert($email, $pass);

                //var_dump($result); // DEBUG

                $errorExist = !is_null($result[1]);
                if ($errorExist) {
                    return $this->message($errorExist);
                }
                else {
                    return $this->message('user signuped');
                }
            } 
        }

        function checkoutEmail($email) {
            $query = new querys();
            $userData = $query->getBy($email);
            $existRow = $userData->rowCount() ? true : false;
            return $existRow;
        }

        function getUserAlls() {
            $list["users"] = array();
            $query = new querys();
            $users = $query->getAll();
            $existRow = $users->rowCount();

           if ($existRow) {
                $arrayIndexedByColumns = $userData->fetch(PDO::FETCH_ASSOC);
                $arrayUser['user'] = $arrayIndexedByColumns;
                $jsonUser = json_encode($arrayUser);
                return $jsonUser;
            }
            else {
                return $this->message("don't exist elements");
            }
        }

        function getUserByEmail($email) {
            $query = new querys();

            $userData = $query->getBy($email);
            $existRow = $userData->rowCount();
            
            if ($existRow) {
                $arrayIndexedByColumns = $userData->fetch(PDO::FETCH_ASSOC);
                $arrayUser['user'] = $arrayIndexedByColumns;
                $jsonUser = json_encode($arrayUser);
                return $jsonUser;
            }
            else {
                return $this->message("don't exist elements");
            }
        }
    }
?>