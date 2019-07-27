<?php

use V2\Modules\Route;

Route::midd('Auth')->post('/accounts/login/refresh', 'AccountController::loginRefresh');

Route::post('/accounts/login/oauth2', 'AccountController::loginOauth');

Route::post('/accounts/login', 'AccountController::login');

Route::post('/accounts/recovery', 'AccountController::recovery');

Route::post('/accounts/resend_email', 'AccountController::resendEmail');

Route::post('/accounts/activation', 'AccountController::activation');

Route::midd('Auth')->put('/accounts/update/email', 'ConfigController::updateEmail');

Route::midd('Auth')->put('/accounts/update/password', 'ConfigController::updatePassword');
