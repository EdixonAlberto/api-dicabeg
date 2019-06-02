<?php

use V2\Modules\Route;
use V2\Modules\Middleware;
use V2\Controllers\VideoController as Controller;

Route::get('/videos/id', function () {
    Middleware::authetication();
    Controller::show();
});

Route::get('/videos/group/nro', function () {
    Middleware::authetication();
    Controller::index();
});
