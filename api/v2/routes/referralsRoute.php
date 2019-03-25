<?php

use V2\Modules\Route;

$referredController = null;//new V2\Controllers\ReferredController;

Route::get('/users/id/referrals', $referredController);
Route::get('/users/id/referrals/id', $referredController);
Route::delete('/users/id/referrals/id', $referredController);
