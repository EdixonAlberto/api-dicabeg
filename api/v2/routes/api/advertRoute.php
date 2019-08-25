<?php

use V2\Modules\Route;
use V2\Modules\JsonResponse;

Route::group(['Auth' => CLIENT], function (Route $route) {
    $route->put('/adverts/bonus/pay', 'AdvertsController:payBonus');
    $route->put('/adverts/enterprise/pay', 'AdvertsController::payEnterprise');
});

Route::get('/adverts/grant/user-id/{userId}/rewards/{rewards}/event-id/{eventId}', function () {
    JsonResponse::OK('granted access');
});
