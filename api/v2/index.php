<?php

require(__DIR__ . '../../../vendor/autoload.php');

V2\Modules\Request::validate();
// requireToken() ? : Sessions::verefit(); --> Modules\Auth::token(); TODO:

define('METHOD', $_SERVER['REQUEST_METHOD']);
define('REQUEST', $_SERVER['REQUEST_URI']);

try {
    require('./routes/' . RESOURCE . 'Route.php');
} catch (\Exception $error) {
    Modules\JsonResponse::error(
        $error->getMessage(),
        $error->getCode()
    );
}
