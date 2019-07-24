<?php

require __DIR__ . '../../../vendor/autoload.php';

use V2\Modules\Requests;
use V2\Libraries\PhpDotEnv;
use V2\Modules\JsonResponse;
use V2\Modules\RouteManager;

new PhpDotEnv;
$routeManager = new RouteManager;
$resource = $routeManager->getResource();

try {
    if ($resource) require 'routes/api/' . $resource . 'Route.php';
    throw new Exception('request incorrect', 400);
} catch (Exception $error) {
    JsonResponse::error(
        $error->getMessage(),
        $error->getCode() ?: 401
    );
}
