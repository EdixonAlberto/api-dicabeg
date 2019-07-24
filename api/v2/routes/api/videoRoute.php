<?php

use V2\Modules\Route;

Route::midd('Auth')->get('/videos/page/{nro}', 'VideoController::index');

Route::midd('Auth')->get('/videos/{id}', 'VideoController::show');
