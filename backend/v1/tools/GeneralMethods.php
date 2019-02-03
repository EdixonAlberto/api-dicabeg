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
        } else throw new Exception('There is no data', 400);

        return $arrayResponse;
    }

    public static function processById($query)
    {
        $row = $query->rowCount();

        if ($row) {
            $arrayIndexedByColumns = $query->fetch(PDO::FETCH_ASSOC);
            $arrayResponse[] = $arrayIndexedByColumns;
        } else throw new Exception('id not exist', 400);

        return $arrayResponse;
    }
}
