<?php

class GeneralMethods
{
    public static function processAlls($query)
    {
        $rows = $query->rowCount();

        if ($rows) {
            for ($i = 0; $i < $rows; $i++) {
                $arrayIndexedByColumns = $query->fetch(PDO::FETCH_ASSOC);
                $arrayResponse[] = $arrayIndexedByColumns;
            }
        } else return false;

        return $arrayResponse;
    }

    public static function processById($query)
    {
        $row = $query->rowCount();

        if ($row) {
            $objIndexedByColumns = $query->fetch(PDO::FETCH_OBJ);
        } else return false;

        return $objIndexedByColumns;
    }

    public static function processJson($query)
    {
        $rows = $query->rowCount();

        if ($rows) {
            $objIndexedByColumns = $query->fetch(PDO::FETCH_OBJ);
            $strJson = $objIndexedByColumns->json;
            $arrayResponse = json_decode($strJson);

        } else return false;

        return $arrayResponse;
    }
}
