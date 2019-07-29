<?php

use V2\Modules\Route;

Route::midd('Auth')->get('/videos/page/{nro}/date-order/{order}', 'VideoController::index');

Route::midd('Auth')->get('/videos/{id}', 'VideoController::show');
