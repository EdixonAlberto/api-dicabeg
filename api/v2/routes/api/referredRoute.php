<?php

use V2\Modules\Route;

Route::midd('Auth')->get('/referreds/page/{nro}/date-order/{order}', 'ReferredController::index');

Route::midd('Auth')->get('/referreds/{id}', 'ReferredController::show');

Route::midd('Auth')->delete('/referreds/{id}', 'ReferredController::destroy');
