<?php

require __DIR__ . '../../../vendor/autoload.php';

try {
    // requireToken() ? : Sessions::verefit(); --> Modules\Auth::token(); TODO:

    //TODO: mejorar
    V2\Modules\Request::validate();


    require './routes/' . RESOURCE . 'Route.php';
    throw new \Exception('route incorrect', 400);

} catch (\Exception $error) {
    V2\Modules\JsonResponse::error(
        $error->getMessage(),
        $error->getCode()
    );
}
