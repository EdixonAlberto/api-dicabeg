<?php

use V2\Modules\Route;
use V2\Modules\Middleware;
use V2\Controllers\TransferController as Controller;

Route::post('/transfers', function ($req) {
    Middleware::authetication();
    Controller::store($req->body);
});

Route::get('/transfers/group/nro', function () {
    Middleware::authetication();
    Controller::index();
});

Route::get('/transfers/id', function () {
    Middleware::authetication();
    Controller::show();
});

Route::get('/transfers', function () {
    Controller::info();
});
