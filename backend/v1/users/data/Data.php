<?php

require_once 'DataQuerys.php';

class Data
{
    public static function getDataAlls()
    {
        $data = DataQuerys::selectAlls();
        if ($data) JsonResponse::read('data', $data);
        else throw new Exception('not found resourse', 404);
    }

    public static function getDataById()
    {
        $data = DataQuerys::selectById();
        if ($data) JsonResponse::read('data', $data);
        else throw new Exception('not found resourse', 404);
    }

    public static function updateData()
    {
        $oldData = (array)DataQuerys::selectById();
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
        DataQuerys::update($arrayData);

        $data = DataQuerys::selectById();
        JsonResponse::updated('data', $data);
    }
}
