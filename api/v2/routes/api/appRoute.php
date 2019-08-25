<?php

use V2\Modules\Route;

Route::group(['Auth' => ADMIN], function (Route $route) {
    $route->get('/app/roles', 'AppController::getRoles');
    $route->get('/app/ranking', 'AppController::getRanking');
    $route->get('/app/balances', 'AppController::totalBalance');
    $route->get('/app/commissions', 'AppController::commissions');
    $route->get('/app/users/page/{nro}', 'UserController::index');
});
