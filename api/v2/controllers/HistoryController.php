<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Database\Querys;
use V2\Modules\JsonResponse;
use V2\Interfaces\IController;

class HistoryController implements IController
{
    public static function index() : void
    {
        $arrayHistory_id = Querys::table('history')
            ->select('video_id')
            ->where('user_id', USERS_ID)
            ->group(GROUP_NRO)
            ->getAll(function () {
                throw new Exception('history not found', 404);
            });

        foreach ($arrayHistory_id as $history) {
            $arrayHistory[] = (array)Querys::table('videos')
                ->select(['video_id', 'name', 'link', 'provider_logo'])
                ->where('video_id', $history->video_id)->get();
        }

        JsonResponse::read($arrayHistory);
    }

    public static function show() : void
    {
        Querys::table('history')
            ->select('video_id')
            ->where([
                'user_id' => USERS_ID,
                'video_id' => HISTORY_ID
            ])->get(function () {
                throw new Exception('history not found', 404);
            });

        $history = Querys::table('videos')
            ->select(['video_id', 'name', 'link', 'provider_logo'])
            ->where('video_id', HISTORY_ID)->get();

        JsonResponse::read((array)$history);
    }

    public static function store($body) : void
    {
        $historyQuery = Querys::table('history');

        $total_views = $historyQuery
            ->select('total_views')
            ->where([
                'user_id' => USERS_ID,
                'video_id' => HISTORY_ID
            ])->get();

        if ($total_views !== false) {
            $historyQuery->update($history = [
                'total_views' => ++$total_views,
                'update_date' => Time::current()->utc
            ])->where([
                'user_id' => USERS_ID,
                'video_id' => HISTORY_ID
            ])->execute();

        } else {
            $historyQuery->insert($history = [
                'user_id' => USERS_ID,
                'video_id' => HISTORY_ID,
                'total_views' => 1,
                'update_date' => Time::current()->utc
            ])->execute();
        }

        $views = Querys::table('videos')
            ->select('total_views')
            ->where('video_id', HISTORY_ID)->get();

        Querys::table('videos')->update([
            'total_views' => ++$views
        ])->where('video_id', HISTORY_ID)->execute();

        $path = 'https://' . $_SERVER['SERVER_NAME'] . '/v2/history/' . HISTORY_ID;

        JsonResponse::created($history, $path);
    }

    public static function update($body) : void
    {
    }

    public static function destroy() : void // ERROR: sin respuesta al borrar todo
    {
        $historyQuery = Querys::table('history');

        if (defined('HISTORY_ID')) {
            $historyQuery->delete()->where([
                'user_id' => USERS_ID,
                'video_id' => HISTORY_ID
            ]);

        } else $historyQuery->delete()->where('user_id', USERS_ID);

        $historyQuery->execute(function () {
            throw new Exception('history not found', 404);
        });

        JsonResponse::removed();
    }
}
