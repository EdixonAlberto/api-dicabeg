<?php

require_once 'VideosQuerys.php';

class Videos
{
    public static function getVideosAlls()
    {
        $arrayVideos = self::selectAlls();
        if ($arrayVideos) {
            for ($i = 0; $i < count($arrayVideos); $i++) {
                $video = $arrayVideos[$i];
                $video->answers_data = json_decode($video->answers_data);
                $_arrayVideos[] = $video;
            }
            JsonResponse::read('videos', $_arrayVideos);

        } else throw new Exception('not found resource', 404);
    }
}
