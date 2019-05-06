<?php

use V2\Modules\Route;
use V2\Controllers\UserController as Controller;

Route::get('/users', function () {
    Controller::show();
});

Route::post('/users', function ($req) {

    Controller::store($req->body);
});

Route::patch('/users', function ($req) {
    Controller::update($req->body);
});

Route::delete('/users', function () {
    Controller::destroy();
});

Route::get('/users/group/nro', function () {
    Controller::index();
});
