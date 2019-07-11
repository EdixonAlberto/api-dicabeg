<?php

use V2\Modules\Route;

Route::midd('Auth')->post('/accounts/login/refresh', 'AccountController::loginRefresh');

// Route::midd('Auth')->get('/accounts/invite_code', 'AccountController::inviteCode'); // TODO: ??

Route::post('/accounts/login/oauth2', 'AccountController::loginOauth');

Route::post('/accounts/login', 'AccountController::login');

Route::post('/accounts/recovery', 'AccountController::recovery');

Route::post('/accounts/send_email', 'AccountController::sendEmail');

Route::post('/accounts/activation', 'AccountController::activation');
