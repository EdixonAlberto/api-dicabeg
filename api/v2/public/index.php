<?php

require __DIR__ . '../../../../vendor/autoload.php';

use V2\Modules\JsonResponse;
use V2\Modules\RouteManager;

new \Libraries\PhpDotEnv;

$routeManager = new RouteManager;
$path = $routeManager->getResource();

try {
    if ($path) require_once "../routes/api/{$path}Route.php";
    throw new Exception('request incorrect', 400);
} catch (Exception $error) {
    JsonResponse::error(
        $error->getMessage(),
        $error->getCode() ?: 401
    );
}
