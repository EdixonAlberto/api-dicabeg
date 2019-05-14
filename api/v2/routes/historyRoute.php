<?php

use V2\Modules\Route;
use V2\Controllers\HistoryController as Controller;

Route::get('/history/group/nro', function () {
    Controller::index();
});

Route::get('/history/id', function () {
    Controller::show();
});

Route::post('/history/id', function ($req) {
    Controller::store($req->body);
});

route::delete('/history/id', function () {
    Controller::destroy();
});

route::delete('/history', function () {
    Controller::destroy();
});
