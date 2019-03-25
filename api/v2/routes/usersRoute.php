<?php

use V2\Modules\Route;

$userController = new V2\Controllers\UserController;

Route::get("/users", $userController);
Route::get("/users/id", $userController);
// Route::post("/users", $userController);
// Route::patch("/users/id", $userController);
// Route::delete("/users/id", $userController);
