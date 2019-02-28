<?php

namespace V1\Videos;

use Db\Querys;
use Exception;
use Tools\JsonResponse;

class Videos
{
    protected const SET = 'video_id, name, link, provider_logo, question, correct, responses, video_views, create_date';

    public static function getVideosAlls()
    {
        $videoQuery = new Querys('videos');
        $arrayVideos = $videoQuery->selectAll(self::SET);
        if ($arrayVideos) {
            foreach ($arrayVideos as $value) {
                $video = $value;
                $video->responses = json_decode($video->responses);
                $_arrayVideos[] = $video;
            }
            JsonResponse::read('videos', $_arrayVideos);
        } else throw new Exception('not found resource', 404);
    }
}
