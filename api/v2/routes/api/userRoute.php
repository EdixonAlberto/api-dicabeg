<?php

use V2\Modules\Route;

Route::group('Auth', function (Route $route) {
    $route->patch('/users', 'UserController::update');
    $route->get('/users', 'UserController::show');
    $route->delete('/users', 'UserController::destroy');
});

Route::post('/users', 'UserController::store');
