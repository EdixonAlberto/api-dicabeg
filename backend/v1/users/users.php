<?php

require_once '../GeneralQuerys.php';
require_once '../tools/Gui.php';
require_once 'UsersQuerys.php';
require_once 'data/DataQuerys.php';

class Users extends UsersQuerys{

    public static function getUsersAlls() {
        $query = self::getAlls();
        $arrayResponse = GeneralQuerys::getAlls($query);

        return self::response($arrayResponse);
    }

    public static function getUsersById($id) {
        $query = self::getById($id);
        $arrayResponse = GeneralQuerys::getById($query);

        return self::response($arrayResponse);
    }

    public static function signUp($PUT) {
        $existUser = self::checkout($PUT);

        if (!$existUser) {
            //::::::::: llenar user
            $arrayUserNew[] = Gui::generate();
            foreach ($PUT as $value) {
                $arrayUserNew[] = $value;
            }
            $arrayUserNew[] = null;
            $arrayUserNew[] = null;
            // $arrayUserNew[] = TimeStamp::timeGet();c
            //::::::::::

            $result = self::insert($arrayUserNew);
            $UserOperation = self::interpretResult($result);

            // Se prepara los datos nulos para intrducirlos a la tabla "users_data"
            if ($UserOperation === true) {
                $arraySetNull[] = $arrayUserNew[0];
                for ($i = 0; $i < 9; $i++) {
                    $arraySetNull[] = null;
                }

                $result = DataQuerys::insert($arraySetNull);
                $dataOperation = self::interpretResult($result);

                if ($dataOperation === true) {
                    http_response_code(200);
                    $arrayResponse[] = [
                        'User_id' => $arrayUserNew[0],
                        'Email' => $arrayUserNew[1],
                        'Password' => $arrayUserNew[2],
                        'Create_Date' => $arrayUserNew[3],
                        'Update_Date' => $arrayUserNew[4]
                    ];
                }
                else {
                    http_response_code(400);
                    $arrayResponse[] = ['Error' => $dataOperation];
                }
            }
            else {
                http_response_code(400);
                $arrayResponse[] = ['Error' => $UserOperation];
            }
        }
        else {
            http_response_code(400);
            $arrayResponse[] = ['Error' => 'User Exist'];
        }

        return self::response($arrayResponse);
    }

    public static function userUpdate($PATCH) {
        $existUser = self::checkout($PATCH);

        if ($existUser) {
            foreach ($PATCH as $key => $value) {
                if ($key == 'id') {
                    $id = $value;
                }
                else if ($key == 'email' or $key == 'password') {
                    $columnKey = $key;
                    $columnValue = $value;
                }
            }
            $timeStamp = null; // TimeStamp::timeGet();

            $result = self::update($id, $columnKey, $columnValue, $timeStamp);
            $UserOperation = self::interpretResult($result);

            if ($UserOperation === true) {
                http_response_code(200);
                $arrayResponse[] = [
                    'User_id' => $id,
                    ucfirst($columnKey) => $columnValue,
                    'Update_Date' => $timeStamp
                ];
            }
        }
        else {
            http_response_code(400);
            $arrayResponse[] = ['Error' => "There is no user with id = {$PATCH['id']}"];
        }

        return self::response($arrayResponse);
    }







    function signIn($PUT) {
        $existUser = self::checkout($PUT);

        if ($existUser) {
            array_shift($PUT); // se borra el primer valor del array: email
            foreach ($PUT as $key => $value) {
                $result = self::getBy($value, $key);
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

        return self::response($arrayResponse);
    }





    // FUNCTIONS INTERNAS---------------------------------------------

    private function interpretResult($result) {
        $error = $result->errorInfo();
        $errorExist = !is_null($error[1]);

        return $errorExist ? $error[2] : true;
    }

    private function checkout($PUT) {
        foreach ($PUT as $key => $value) {
            $result = self::getById($value, $key);
            $rows = $result->rowCount();

            return $rows ? true : false;
        }
    }

    private function response($_arrayResponse) {
        $arrayResponse['users'] = $_arrayResponse;

        return json_encode($arrayResponse);
    }
}
