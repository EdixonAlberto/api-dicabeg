<?php

namespace V2\Controllers;

use Exception;

use V2\Database\Querys;
use V2\Modules\JsonResponse;
use V2\Interfaces\IController;

class VideoController implements IController
{
    public static function index($req) : void
    {
        $arrayVideos = Querys::table('videos')
            ->select(self::VIDEOS_COLUMNS)
            ->group($req->params->nro)
            ->getAll(function () {
                throw new Exception('videos not exist', 404);
            });

        foreach ($arrayVideos as $videos) {
            foreach ($videos as $key => $value) {
                $_videos[$key] = ($key == 'responses') ?
                    json_decode($value) : $value;
            }
            $_arrayVideos[] = $_videos;
        }

        JsonResponse::read($_arrayVideos);
    }

    public static function show($req) : void
    {
        $video = Querys::table('videos')
            ->select(self::VIDEOS_COLUMNS)
            ->where('video_id', $req->params->id)
            ->get(function () {
                throw new Exception('video not found', 404);
            });

        foreach ($video as $key => $value) {
            $_video[$key] = ($key == 'responses') ?
                json_decode($value) : $value;
        }

        JsonResponse::read($_video);
    }

    public static function store($req) : void
    {
    }

    public static function update($req) : void
    {
    }

    public static function destroy($req) : void
    {
    }
}
