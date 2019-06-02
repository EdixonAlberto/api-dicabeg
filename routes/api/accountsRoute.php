<?php

use V2\Modules\Route;
use V2\Modules\Middleware;
use V2\Controllers\AccountController as Controller;

Route::post('/accounts/login/refresh', function () {
    Middleware::authetication();
    Controller::refreshLogin();
});

Route::get('/accounts/invite_code', function () {
    Middleware::authetication();
    Controller::show();
});

Route::post('/accounts/login/oauth2', function () {
    Controller::oauthLogin();
});

Route::post('/accounts/login', function ($req) {
    Controller::userLogin($req->body);
});

Route::post('/accounts/recovery', function ($req) {
    Controller::passwordRecovery($req->body);
});

Route::post('/accounts/send_email', function ($req) {
    Middleware::input($req->body);
    Controller::sendEmail($req->body);
});

Route::post('/accounts/activation', function ($req) {
    Controller::activateAccount($req->body);
});
