<?php

class HistoryController extends Querys
{
   private const SET = 'history_id, user_id, video_id, history_views, update_date';

   public static function index()
   {
      $historyQuery = new Querys('history');
      $videoQuery = new Querys('videos');

      $arrayHistory = $historyQuery->select('user_id', $_GET['id'], 'video_id, history_views, update_date');
      if ($arrayHistory) {
         foreach ($arrayHistory as $history) {
            $video_id = $history->video_id;
            $arrayVideo = $videoQuery->select('video_id', $video_id, 'name, link, provider_logo');

            $_history = $arrayVideo[0];
            $_history->views = $history->history_views;
            $_history->date = $history->update_date;

            $_arrayHistory[] = $_history;
         }
      } else throw new Exception('not found resourse', 404);

      JsonResponse::read('history', $_arrayHistory);
   }

   public static function show()
   {
      $historyQuery = new Querys('history');
      $videoQuery = new Querys('videos');

      $history_id = $_GET['id'] . $_GET['id_2'];
      $arrayHistory = $historyQuery->select('history_id', $history_id, 'video_id, history_views, update_date');

      if ($arrayHistory) {
         $history = $arrayHistory[0];
         $video_id = $history->video_id;
         $arrayVideo = $videoQuery->select('video_id', $video_id, 'name, link, provider_logo');

         $_history = $arrayVideo[0];
         $_history->views = $history->history_views;
         $_history->date = $history->update_date;

      } else throw new Exception('not found resourse', 404);

      JsonResponse::read('history', $_history);
   }

   public static function store()
   {
      $historyQuery = new Querys('history');

      $history_id = $_GET['id'] . $_GET['id_2'];
      $arrayHistory = $historyQuery->select('history_id', $history_id, 'history_views');

      if ($arrayHistory) {
         $history = $arrayHistory[0];
         $_arrayHistory['history_views'] = ++$history->history_views;
         $historyQuery->update('history_id', $history_id, $_arrayHistory);
      } else {
         $_history = [
            'history_id' => $_GET['id'] . $_GET['id_2'],
            'user_id' => $_GET['id'],
            'video_id' => $_GET['id_2']
         ];
         $historyQuery->insert($_history);
      }

      $history = $historyQuery->select('history_id', $history_id, 'history_id, history_views, create_date, update_date');
      JsonResponse::created('history', $history);
   }

   public static function destroy()
   {
      $historyQuery = new Querys('history');

      $history_id = $_GET['id'] . $_GET['id_2'];
      $historyQuery->delete('history_id', $history_id);
      JsonResponse::removed();
   }
}
