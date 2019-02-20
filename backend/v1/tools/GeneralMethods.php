<?php

class GeneralMethods
{
    public static function processSelect($query, $getError = true)
    {
        $rows = $query->rowCount();

        if ($rows > 1) {
            for ($i = 0; $i < $rows; $i++) {
                $objIndexedByColumns = $query->fetch(PDO::FETCH_OBJ);
                $arrayResponse[] = $objIndexedByColumns;
            }
        } else if ($rows == 1) {
            $arrayResponse = $query->fetch(PDO::FETCH_OBJ);
        } else if ($getError) {
            throw new Exception('not found resourse', 404);
        } else return false;

        return $arrayResponse;
    }

    public static function processQuery($result)
    {
        $error = $result->errorInfo();
        $errorExist = !is_null($error[1]);
        if ($errorExist) {
            throw new Exception($error[2], 400);
        }
    }

    public static function processDelete($query)
    {
        $rows = $query->rowCount();
        if ($rows >= 1) {
        } else throw new Exception('delete failed', 500);
    }
}
