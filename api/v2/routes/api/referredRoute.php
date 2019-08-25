<?php

use V2\Modules\Route;

Route::group(['Auth' => CLIENT], function (Route $route) {
    $route->get('/referreds/page/{nro}/date-order/{order}', 'ReferredController::index');
    $route->get('/referreds/{id}', 'ReferredController::show');
    $route->delete('/referreds/{id}', 'ReferredController::destroy');
});
