<?php

namespace V1\Users\History;

use Db\Querys;
use Tools\Constants;
use Tools\JsonResponse;
use V1\Options\Time;

class History extends Constants
{
   public static function index()
   {
      $historyQuery = new Querys('history');

      $arrayHistory = $historyQuery->select('user_id', $_GET['id'], 'video_id, total_views, update_date', true);
      if ($arrayHistory == false) throw new \Exception('not found resourse', 404);

      foreach ($arrayHistory as $history) {
         $history_data = self::getHistoryData($history);
         $_arrayHistory[] = $history_data;
      }
      JsonResponse::read('history', $_arrayHistory);
   }

   public static function show()
   {
      $historyQuery = new Querys('history');

      $history_id = $_GET['id'] . $_GET['id_2'];
      $history = $historyQuery->select('history_id', $history_id, 'video_id, total_views, update_date');
      if ($history == false) throw new \Exception('not found resourse', 404);

      $_history = self::getHistoryData($history);
      JsonResponse::read('history', $_history);
   }

   public static function store()
   {
      $historyQuery = new Querys('history');
      $videoQuery = new Querys('videos');

      $history_id = $_GET['id'] . $_GET['id_2'];
      $history = $historyQuery->select('history_id', $history_id, 'total_views');
      $video = $videoQuery->select('video_id', $_GET['id_2'], 'total_views');

      if ($history) {
         $_arrayHistory = [
            'total_views' => ++$history->total_views,
            'update_date' => Time::current('UTC')
         ];
         $historyQuery->update('history_id', $history_id, $_arrayHistory);
      } else {
         $_arrayHistory = [
            'history_id' => $_GET['id'] . $_GET['id_2'],
            'user_id' => $_GET['id'],
            'video_id' => $_GET['id_2'],
            'total_views' => 1,
            'update_date' => Time::current('UTC')
         ];
         $historyQuery->insert($_arrayHistory);
         unset($_arrayHistory['history_id'], $_arrayHistory['user_id']);
      }

      $views = ++$video->total_views;
      $videoQuery->update('video_id', $_GET['id_2'], ['total_views' => $views]);

      JsonResponse::created('history', $_arrayHistory);
   }

   public static function destroy()
   {
      $historyQuery = new Querys('history');

      $history = $historyQuery->select('user_id', $_GET['id']);
      if ($history == false) throw new \Exception('not found resourse', 404);

      if ($_GET['id_2'] == 'alls') {
         $historyQuery->delete('user_id', $_GET['id']);
      } else {
         $history_id = $_GET['id'] . $_GET['id_2'];
         $historyQuery->delete('history_id', $history_id);
      }
      JsonResponse::removed();
   }

   protected static function getHistoryData($history)
   {
      $videoQuery = new Querys('videos');

      $video = $videoQuery->select('video_id', $history->video_id, 'video_id, name, link, provider_logo');
      $video->total_views = $history->total_views;
      $video->update_date = $history->update_date;
      return $video;
   }
}
