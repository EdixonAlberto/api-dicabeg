<?php

require __DIR__ . '../../../vendor/autoload.php';

use V2\Modules\Requests;
use V2\Libraries\PhpDotEnv;
use V2\Modules\JsonResponse;

try {
    PhpDotEnv::loadEnviroment();
    $request = new Requests;

    require 'routes/' . $request->resource . 'Route.php';
    throw new Exception('route incorrect', 400);

} catch (Exception $error) {
    JsonResponse::error(
        $error->getMessage(),
        $error->getCode() ? : 401
    );
}
