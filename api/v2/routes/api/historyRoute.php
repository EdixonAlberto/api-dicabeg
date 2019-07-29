<?php

use V2\Modules\Route;

Route::midd('Auth')->get('/history/page/{nro}/date-order/{order}', 'HistoryController::index');

Route::midd('Auth')->get('/history/{id}', 'HistoryController::show');

Route::midd('Auth')->post('/history/{id}', 'HistoryController::store');

Route::midd('Auth')->delete('/history/{id}', 'HistoryController::destroy');

Route::midd('Auth')->delete('/history', 'HistoryController::destroy');
