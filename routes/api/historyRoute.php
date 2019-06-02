<?php

use V2\Modules\Route;
use V2\Modules\Middleware;
use V2\Controllers\HistoryController as Controller;

Route::get('/history/group/nro', function () {
    Middleware::authetication();
    Controller::index();
});

Route::get('/history/id', function () {
    Middleware::authetication();
    Controller::show();
});

Route::post('/history/id', function ($req) {
    Middleware::authetication();
    Controller::store($req->body);
});

route::delete('/history/id', function () {
    Middleware::authetication();
    Controller::destroy();
});

route::delete('/history', function () {
    Middleware::authetication();
    Controller::destroy();
});
