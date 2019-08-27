<?php

use V2\Modules\Route;

Route::midd('Auth')->get('/products/page/{nro}', 'ProductController::index');
Route::midd('Auth')->get('/products/{id}', 'ProductController::show');

Route::group(['Auth' => ENTERPRISE], function (Route $route) {
    $route->post('/products', 'ProductController::store');
    $route->patch('/products/{id}', 'ProductController::update');
    $route->delete('/products/{id}', 'ProductController::destroy');
});
