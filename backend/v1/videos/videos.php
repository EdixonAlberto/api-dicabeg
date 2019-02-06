<?php

require_once 'VideosQuerys.php';

class Videos extends VideosQuerys
{
    public static function getVideosAlls()
    {
        $query = self::selectAlls();
        $result = GeneralMethods::processAlls($query);
        if ($result) {
            return $result;
        } else throw new Exception('Videos does not exist', 400);
    }
}
