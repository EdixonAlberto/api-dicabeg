<?php

require_once '../../tools/GeneralMethods.php';
require_once 'DataQuerys.php';

class Data extends DataQuerys
{
    public static function getDataAlls()
    {
        $query = self::selectAlls();
        return GeneralMethods::processAlls($query);
    }

    public static function getDataById()
    {
        $query = self::selectById();
        return GeneralMethods::processById($query);
    }

    public static function insertData()
    {
        $email = $_REQUEST['email'];
        $lengh = strpos($email, '@');
        $username = substr($email, 0, $lengh);
        $arraySetDefault[] = $username;

        for ($i = 0; $i < 7; $i++) {
            $arraySetDefault[] = null;
        }
        $arraySetDefault[] = null; // TODO: $arraySetDefault[] = TimeStamp::timeGet();

        $result = self::insert($arraySetDefault);
        self::interpretResult($result);
    }

    public static function updateData()
    {
        $dataOld = self::getDataById()[0];
        $dataNew = $_REQUEST['parameters'];

        foreach ($dataNew as $key => $value) {
            foreach ($dataOld as $_key => $_value) {
                if ($_key == $key) {
                    $arrayData[] = $value;
                    continue;
                }
            }
            $arrayData[] = $_value;
        }
        var_dump($arrayData);
        die;
        $result = DataQuerys::update($arrayData);
        self::interpretResult($result);
        return 'Updated User Data';
    }

    // deleteData no esta por

    private function interpretResult($result)
    {
        $error = $result->errorInfo();
        $errorExist = !is_null($error[1]);
        if ($errorExist) {
            throw new Exception($error[2], 400);
        }
    }
}
