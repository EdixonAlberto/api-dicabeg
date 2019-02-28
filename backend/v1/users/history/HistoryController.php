<?php

namespace V1\Users\History;

use Db\Querys;
use Exception;
use Tools\JsonResponse;

class HistoryController extends Querys
{
   private const SET = 'history_id, user_id, video_id, history_views, update_date';
   protected const TIME = 'Y-m-d H:i:00';

   public static function index()
   {
      $historyQuery = new Querys('history');

      $arrayHistory = $historyQuery->select('user_id', $_GET['id'], 'video_id, history_views, update_date');
      if ($arrayHistory == false) throw new Exception('not found resourse', 404);

      foreach ($arrayHistory as $history) {
         $history_data = self::getHistoryData($history);
         $_arrayHistory[] = $history_data;
      }
      JsonResponse::read('history', $_arrayHistory);
   }

   public static function show()
   {
      $historyQuery = new Querys('history');
      $videoQuery = new Querys('videos');

      $history_id = $_GET['id'] . $_GET['id_2'];
      $arrayHistory = $historyQuery->select('history_id', $history_id, 'video_id, history_views, update_date');
      if ($arrayHistory == false) throw new Exception('not found resourse', 404);

      $_history = self::getHistoryData($arrayHistory[0]);
      JsonResponse::read('history', $_history);
   }

   public static function store()
   {
      $historyQuery = new Querys('history');

      $history_id = $_GET['id'] . $_GET['id_2'];
      $arrayHistory = $historyQuery->select('history_id', $history_id, 'history_views');

      date_default_timezone_set('America/Caracas');
      if ($arrayHistory) {
         $history = $arrayHistory[0];

         $_arrayHistory = [
            'history_views' => ++$history->history_views,
            'update_date' => date(self::TIME)
         ];
         $historyQuery->update('history_id', $history_id, $_arrayHistory);

      } else {
         $_arrayHistory = [
            'history_id' => $_GET['id'] . $_GET['id_2'],
            'user_id' => $_GET['id'],
            'video_id' => $_GET['id_2'],
            'history_views' => 1,
            'update_date' => date(self::TIME)
         ];
         $historyQuery->insert($_arrayHistory);
      }
      JsonResponse::created('history', $_arrayHistory);
   }

   public static function destroy()
   {
      $historyQuery = new Querys('history');

      $history_id = $_GET['id'] . $_GET['id_2'];
      $arrayHistory = $historyQuery->select('history_id', $history_id);
      if ($arrayHistory == false) throw new Exception('not found resourse', 404);

      $historyQuery->delete('history_id', $history_id);
      JsonResponse::removed();
   }

   protected static function getHistoryData($history)
   {
      $videoQuery = new Querys('videos');

      $video_id = $history->video_id;
      $arrayVideo = $videoQuery->select('video_id', $video_id, 'video_id, name, link, provider_logo');

      $_history = $arrayVideo[0];
      $_history->views = $history->history_views;
      $_history->date = $history->update_date;

      return $_history;
   }
}
