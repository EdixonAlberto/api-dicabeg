<?php

require __DIR__ . '../../../vendor/autoload.php';

use V2\Modules\Requests;
use V2\Libraries\PhpDotEnv;
use V2\Modules\JsonResponse;

$request = new Requests;
new PhpDotEnv;

try {

    require 'routes/' . RESOURCE . 'Route.php';
    throw new Exception('route incorrect', 400);

} catch (Exception $error) {
    JsonResponse::error(
        $error->getMessage(),
        $error->getCode() ? : 401
    );
}
