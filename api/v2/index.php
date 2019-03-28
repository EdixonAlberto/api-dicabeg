<?php

require __DIR__ . '../../../vendor/autoload.php';

V2\Libraries\PhpDotEnv::load();

try {
    V2\Modules\Request::validate();
    V2\Modules\Auth::prosecute(); // TODO:

    require './routes/' . RESOURCE . 'Route.php';
    throw new \Exception('route incorrect', 400);

} catch (\Exception $error) {
    V2\Modules\JsonResponse::error(
        $error->getMessage(),
        $error->getCode()
    );
}
