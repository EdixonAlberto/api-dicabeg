<?php

use V2\Modules\Route;
use V2\Modules\JsonResponse;

Route::put('/adverts/bonus/pay', 'AdvertsController:payBonus');

Route::put('/adverts/enterprise/pay', 'AdvertsController::payEnterprise');

Route::get('/adverts/grant/user-id/{userId}/rewards/{rewards}/event-id/{eventId}', function () {
    JsonResponse::OK('granted access');
});
