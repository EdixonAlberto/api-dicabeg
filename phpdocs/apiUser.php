<?php
    include 'querys.php';

    class apiUser {

        function newUser($email, $pass) {
            $emailExist = $this->checkoutEmail($email);

            if ($emailExist) {
                return $this->message('email exist');
            }
            else {
                $query = new querys();
                $resul = $query->signUp($email, $pass);
                print_r($resul);
                // if ($query) {}
                // return $this->message('user signuped');
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
            $users = $query->get();
            $existRow = $users->rowCount();

            if ($existRow) {
                foreach ($users as $row) {
                    $item = array(
                        "name"      => $row["name"],
                        "age"       => $row["age"],
                        "nir"       => $row["nir"],
                        "phone"     => $row["phone"]
                    );
                    array_push($list["users"], $item);
                }
                return $this->result($list);
            } else {
                return $this->message("don't exist elements");
            }
        }

        function getUserData() {
            $list["users"] = array();
            $query = new querys();
            $users = $query->get(null);
            $existRow = $users->rowCount();

            if ($existRow) {
                foreach ($users as $row) {
                    $item = array(
                        "name"      => $row["name"],
                        "age"       => $row["age"],
                        "nir"       => $row["nir"],
                        "phone"     => $row["phone"]
                    );
                    array_push($list["users"], $item);
                }
                return $this->result($list);
            } else {
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

        function remove($table) {

        }

        function message($message) {
            echo json_encode(array("message" => $message));
        }
    }
?>