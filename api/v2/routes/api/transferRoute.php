<?php

use V2\Modules\Route;

Route::midd('Auth')->post('/transfers', 'TransferController::store');

Route::midd('Auth')->get('/transfers/page/{nro}', 'TransferController::index');

Route::midd('Auth')->get('/transfers/{code}', 'TransferController::show');

Route::midd('Auth')->post('/transfers/send_report', 'TransferController::send_report');
