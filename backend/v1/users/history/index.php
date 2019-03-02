<?php

require_once __DIR__ . '../../../../../vendor/autoload.php';

use Tools\JsonResponse;
use V1\Sessions\Sessions;
use V1\Users\History\HistoryController;

$method = $_SERVER['REQUEST_METHOD'];
parse_str(file_get_contents('php://input'), $_REQUEST);

try {
   Sessions::verifySession();

   switch ($method) {
      case 'GET':
         ($_GET['id_2'] == 'alls') ?
            HistoryController::index() :
            HistoryController::show();
         break;

      case 'POST':
         HistoryController::store();
         break;

      case 'DELETE':
         HistoryController::destroy(); // TODO: Eliminar todo
         break;
   }
} catch (Exception $error) {
   $response = $error->getMessage();
   $code = $error->getCode();
   JsonResponse::error($response, $code);
}
