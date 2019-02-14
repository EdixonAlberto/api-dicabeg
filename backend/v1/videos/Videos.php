<?php

require_once 'VideosQuerys.php';

class Videos extends VideosQuerys
{
    public static function getVideosAlls()
    {
        $videos = self::selectAlls();
        if ($videos) JsonResponse::read('videos', $videos);
        else throw new Exception('not found resource', 404);
    }
}
