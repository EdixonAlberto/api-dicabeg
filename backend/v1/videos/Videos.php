<?php

namespace V1\Videos;

use Db\Querys;
use Tools\Constants;
use Tools\JsonResponse;

class Videos extends Constants
{
    public static function index()
    {
        $videoQuery = new Querys('videos');

        $arrayVideos = $videoQuery->selectAll(self::SET_VIDEOS);
        if ($arrayVideos == false) throw new \Exception('not found videos', 404);

        foreach ($arrayVideos as $video) {
            $video->responses = json_decode($video->responses);
            $_arrayVideos[] = $video;
        }
        JsonResponse::read('videos', $_arrayVideos);
    }

    public static function show()
    {
        $videoQuery = new Querys('videos');

        $video = $videoQuery->select('video_id', $_GET['id'], self::SET_VIDEOS);
        if ($video == false) throw new \Exception('not found video', 404);

        $video->responses = json_decode($video->responses);
        JsonResponse::read('video', $video);
    }
}
