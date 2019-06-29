<?php

use V2\Modules\Route;
use V2\Modules\Middleware;
use V2\Controllers\UserController as Controller;

Route::patch('/users', function ($req) {
    Middleware::authetication();
    Controller::update($req->body);
});

Route::post('/users', function ($req) {
    Controller::store($req->body);
});

Route::get('/users', function () {
    Middleware::authetication();
    Controller::show();
});

Route::put('/users/config', function ($req) {
    Middleware::authetication();
    Controller::config($req->body);
});

Route::delete('/users', function () {
    Middleware::authetication();
    Controller::destroy();
});

Route::get('/users/group/nro', function () {
    Middleware::authetication();
    Controller::index();
});
