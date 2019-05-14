<?php

use V2\Modules\Route;
use V2\Controllers\VideoController as Controller;

Route::get('/videos/id', function () {
    Controller::show();
});

Route::get('/videos/group/nro', function () {
    Controller::index();
});
