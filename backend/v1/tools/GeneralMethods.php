<?php

class GeneralMethods
{
    public static function processSelect($query)
    {
        $rows = $query->rowCount();

        if ($rows > 1) {
            for ($i = 0; $i < $rows; $i++) {
                $objIndexedByColumns = $query->fetch(PDO::FETCH_OBJ);
                $arrayResponse[] = $objIndexedByColumns;
            }
        } else if ($rows == 1) {
            return $query->fetch(PDO::FETCH_OBJ);
        } else return false;

        return $arrayResponse;
    }

    public static function processQuery($result)
    {
        $error = $result->errorInfo();
        $errorExist = !is_null($error[1]);
        if ($errorExist) {
            throw new Exception($error[2], 400);
        } else true;
    }

    // public static function processJson($query)
    // {
    //     $rows = $query->rowCount();

    //     if ($rows) {
    //         $objIndexedByColumns = $query->fetch(PDO::FETCH_OBJ);
    //         $strJson = $objIndexedByColumns->json;
    //         $arrayResponse = json_decode($strJson);

    //     } else return false;

    //     return $arrayResponse;
    // }

    // public static function checkUser($field, &$objUser)
    // {
    //     if ($field == 'id') {
    //         $result = self::selectById();
    //     } else {
    //         $query = AccountsQuerys::select($field, $_REQUEST[$field]);
    //         $result = self::processSingleQuery($query);
    //         $objUser = $result;
    //     }
    //     return (is_object($result)) ? true : false;
    // }
}
