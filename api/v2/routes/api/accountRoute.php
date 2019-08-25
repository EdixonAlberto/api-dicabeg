<?php

use V2\Modules\Route;

Route::post('/accounts/login', 'AccountController::login');
// Route::post('/accounts/login/oauth2', 'AccountController::loginOauth');

Route::group('Auth', function (Route $route) {
    $route->post('/accounts/login/refresh', 'AccountController::loginRefresh');
    $route->put('/accounts/update/password', 'ConfigController::updatePassword');
    $route->put('/accounts/update/email', 'ConfigController::updateEmail');
});

Route::post('/accounts/recovery', 'AccountController::recovery');
Route::post('/accounts/resend_email', 'AccountController::resendEmail');
Route::post('/accounts/activation', 'AccountController::activation');
