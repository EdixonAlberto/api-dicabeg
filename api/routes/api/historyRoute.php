<?php

use V2\Modules\Route;

Route::group('Auth', function (Route $route) {
    $route->get('/history/page/{nro}/date-order/{order}', 'HistoryController::index');
    $route->get('/history/{id}', 'HistoryController::show');
    $route->post('/history/{id}', 'HistoryController::store');
    $route->delete('/history/{id}', 'HistoryController::destroy');
    $route->delete('/history', 'HistoryController::destroy');
});
