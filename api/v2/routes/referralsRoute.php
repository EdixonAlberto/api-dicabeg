<?php

use V2\Modules\Route;
use V2\Modules\Middleware;
use V2\Controllers\ReferredController as Controller;

Route::get('/referrals/group/nro', function () {
    Middleware::authetication();
    Controller::index();
});

Route::get('/referrals/id', function () {
    Middleware::authetication();
    Controller::show();
});

Route::delete('/referrals/id', function () {
    Middleware::authetication();
    Controller::destroy();
});
