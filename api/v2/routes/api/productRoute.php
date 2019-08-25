<?php

use V2\Modules\Route;

Route::midd(['Auth' => CLIENT])->get('/products/page/{nro}', 'ProductController::index');
Route::get('/products/age/{nro}/date-order/{order}', 'ProductController::index');
Route::midd('Auth')->get('/products/page/{nro}/date-order/{order}', 'ProductController::index');

Route::group(['Auth' => ENTERPRISE], function () {
    Route::post('/products', 'ProductController::store');
    Route::put('/products/{id}', 'ProductController::update');
    Route::delete('/products/{id}', 'ProductController::destroy');
});
