<?php

// tools/
// require_once '../tools/Validations.php';
require_once '../../tools/db/Querys.php';
require_once '../../tools/GeneralMethods.php';
require_once '../../tools/JsonResponse.php';
require_once '../../tools/Options.php';

// Resource
require_once '../../sessions/Sessions.php';
require_once './HistoryController.php';

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
         HistoryController::destroy();
         break;
   }
} catch (Exception $error) {
   $response = $error->getMessage();
   $code = $error->getCode();
   JsonResponse::error($response, $code);
}
