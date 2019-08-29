<?php

use V2\Modules\Route;

Route::group('Auth', function (Route $route) {
    $route->get('/videos/page/{nro}/date-order/{order}', 'VideoController::index');
    $route->get('/videos/{id}', 'VideoController::show');
});
