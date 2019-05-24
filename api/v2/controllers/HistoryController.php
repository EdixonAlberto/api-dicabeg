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
        $arrayHistory = Querys::table('history')
            ->select(self::HISTORY_COLUMNS)
            ->where('user_id', USERS_ID)
            ->group(GROUP_NRO)
            ->getAll(function () {
                throw new Exception('history not found', 404);
            });

        foreach ($arrayHistory as $history) {
            $history->video = Querys::table('videos')
                ->select(['video_id', 'name', 'link', 'provider_logo'])
                ->where('video_id', $history->video)->get();

            $_arrayHistory[] = $history;
        }

        JsonResponse::read($_arrayHistory);
    }

    public static function show() : void
    {
        $history = Querys::table('history')
            ->select(self::HISTORY_COLUMNS)
            ->where([
                'user_id' => USERS_ID,
                'video' => HISTORY_ID
            ])->get(function () {
                throw new Exception('history not found', 404);
            });

        $history->video = Querys::table('videos')
            ->select(['video_id', 'name', 'link', 'provider_logo'])
            ->where('video_id', HISTORY_ID)->get();

        JsonResponse::read($history);
    }

    public static function store($body) : void
    {
        $historyQuery = Querys::table('history');

        $total_views = $historyQuery
            ->select('total_views')
            ->where([
                'user_id' => USERS_ID,
                'video' => HISTORY_ID
            ])->get();

        if ($total_views !== false) {
            $historyQuery->update($history = (object)[
                'total_views' => ++$total_views,
                'update_date' => Time::current()->utc
            ])->where([
                'user_id' => USERS_ID,
                'video' => HISTORY_ID
            ])->execute();

        } else {
            $historyQuery->insert($history = (object)[
                'user_id' => USERS_ID,
                'video' => HISTORY_ID,
                'total_views' => 1,
                'update_date' => Time::current()->utc
            ])->execute();
            unset($history->video);
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

    public static function destroy() : void
    {
        $historyQuery = Querys::table('history');

        if (defined('HISTORY_ID')) {
            $historyQuery->delete()->where([
                'user_id' => USERS_ID,
                'video' => HISTORY_ID
            ]);

        } else $historyQuery->delete()->where('user_id', USERS_ID);

        $historyQuery->execute(function () {
            throw new Exception('history not found', 404);
        });

        JsonResponse::removed();
    }
}
