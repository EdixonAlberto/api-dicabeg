<?php

require_once '../../tools/GeneralMethods.php';
require_once 'DataQuerys.php';

class Data extends DataQuerys
{
    public static function getDataAlls()
    {
        $query = self::selectAlls();
        $result = GeneralMethods::processAlls($query);
        if ($result) {
            return $result;
        } else throw new Exception('There are no users', 400);
    }

    public static function getDataById()
    {
        $query = self::selectById($_GET['id']);
        $result = GeneralMethods::processById($query);
        if ($result) {
            return $result;
        } else throw new Exception('User does not exist', 400);
    }

    public static function insertData()
    {
        $email = $_REQUEST['email'];
        $lengh = strpos($email, '@');
        $username = substr($email, 0, $lengh);
        $arrayData[] = $username;

        for ($i = 0; $i < 7; $i++) {
            $arrayData[] = null;
        }

        $result = self::insert($arrayData);
        self::interpretResult($result);
    }

    public static function updateData()
    {
        $oldData = self::getDataById()[0];
        $newData = $_REQUEST;
        // TODO: Revisar esta parte del codigo para optimisar
        foreach ($oldData as $_key => $_value) {
            $_keyFound = false;
            foreach ($newData as $key => $value) {
                if ($_key == $key) {
                    $arrayData[] = $value;
                    $_keyFound = true;
                }
            }
            if (!$_keyFound and $_key != 'user_id') {
                $arrayData[] = $_value;
            }
        }
        $result = DataQuerys::update($arrayData);
        self::interpretResult($result);
        $arrayResponse[] = ['Successful' => 'Updated user data'];

        return $arrayResponse;
    }

    // TODO: Estudiar si es necesario la fuction deleteData

    private static function interpretResult($result)
    {
        $error = $result->errorInfo();
        $errorExist = !is_null($error[1]);
        if ($errorExist) {
            throw new Exception($error[2], 400);
        }
    }
}
