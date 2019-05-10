<?php

use V2\Modules\Route;
use V2\Controllers\ReferredController as Controller;

Route::get('/referrals/id', function () {
    Controller::show();
});

Route::get('/referrals/group/nro', function () {
    Controller::index();
});
