<?php

use V2\Modules\Route;

Route::midd('Auth')->get('/app/ranking', 'AppController::getRanking');

Route::midd('Auth')->get('/app/balances', 'AppController::totalBalance');

Route::midd('Auth')->get('/app/commissions', 'AppController::commissions');
