<?php

require __DIR__ . '../../../vendor/autoload.php';

V2\Library\PhpDotEnv::load();

try {
    V2\Modules\Request::validate();

    // V2\Modules\Auth::token();

    // requireToken() ? : Sessions::verefit(); --> Modules\Auth::token(); TODO:

    require './routes/' . RESOURCE . 'Route.php';

    throw new \Exception('route incorrect', 400);
} catch (\Exception $error) {
    V2\Modules\JsonResponse::error(
        $error->getMessage(),
        $error->getCode()
    );
}
