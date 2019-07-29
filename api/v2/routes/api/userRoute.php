<?php

use V2\Modules\Route;

Route::midd('Auth')->patch('/users', 'UserController::update');

Route::post('/users', 'UserController::store');

Route::midd('Auth')->get('/users', 'UserController::show');

Route::midd('Auth')->delete('/users', 'UserController::destroy');

Route::midd('Auth')->get('/users/page/{nro}/date-order/{order}', 'UserController::index');
