<?php

use V2\Modules\Route;
use V2\Modules\JsonResponse;

Route::post('/videos/test', function ($req) {
    var_dump($_POST, $req->body);
    die;

    return JsonResponse::OK('Body obtenido',  $req);
});

Route::midd('Auth')->get('/videos/page/{nro}/date-order/{order}', 'VideoController::index');

Route::midd('Auth')->get('/videos/{id}', 'VideoController::show');
