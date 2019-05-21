<?php

use V2\Modules\Route;
use V2\Controllers\TransferController as Controller;

Route::post('/transfers', function ($req) {
    Controller::store($req->body);
});

Route::get('/transfers/group/nro', function () {
    Controller::index();
});

Route::get('/transfers/id', function () {
    Controller::show();
});
