<?php

require_once 'VideosQuerys.php';

class Videos
{
    public static function getVideosAlls()
    {
        $arrayVideos = VideosQuerys::selectAlls();
        if ($arrayVideos) {
            for ($i = 0; $i < count($arrayVideos); $i++) {
                $video = $arrayVideos[$i];
                $video->responses = json_decode($video->responses);
                $_arrayVideos[] = $video;
            }
            JsonResponse::read('videos', $_arrayVideos);

        } else throw new Exception('not found resource', 404);
    }
}
