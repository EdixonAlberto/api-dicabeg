<?php

use V2\Modules\Route;

Route::get('/app/roles', 'AppController::getRoles');

Route::midd('Auth')->get('/app/ranking', 'AppController::getRanking');

Route::midd('Auth')->get('/app/balances', 'AppController::totalBalance');

Route::midd('Auth')->get('/app/commissions', 'AppController::commissions');
