<?php

namespace V2\Controllers;

use Exception;
use V2\Database\Querys;
use V2\Modules\JsonResponse;
use V2\Interfaces\ControllerInterface;

class VideoController implements ControllerInterface
{
    public static function index()
    {
        $arrayVideos = Querys::table('videos')
            ->select(VIDEOS_SET)
            ->getAll();

        if ($arrayVideos == false) throw new Exception('not found videos', 404);
        foreach ($arrayVideos as $video) {
            $video->responses = json_decode($video->responses);
            $_arrayVideos[] = $video;
        }
        JsonResponse::read('videos', $_arrayVideos);
    }

    public static function show()
    {
    }

    public static function store()
    {
    }

    public static function update()
    {
    }

    public static function destroy()
    {
    }
}
