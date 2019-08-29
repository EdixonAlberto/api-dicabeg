<?php

require __DIR__ . '../../../../vendor/autoload.php';

use V2\Modules\JsonResponse;
use V2\Modules\RouteManager;

new \Libraries\PhpDotEnv;

try {
    require (new RouteManager)->router;
    throw new \Exception('request incorrect', 400);
} catch (Exception $error) {
    JsonResponse::error(
        $error->getMessage(),
        $error->getCode() ?: 401
    );
}
