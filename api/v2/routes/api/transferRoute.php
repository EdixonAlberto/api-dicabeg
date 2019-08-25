<?php

use V2\Modules\Route;

Route::group('Auth', function (Route $route) {
    $route->post('/transfers', 'TransferController::store');
    $route->get('/transfers/page/{nro}/date-order/{order}', 'TransferController::index');
    $route->get('/transfers/{code}', 'TransferController::show');
    $route->post('/transfers/send_report', 'TransferController::sendReport');
});
