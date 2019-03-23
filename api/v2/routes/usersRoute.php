<?php

use V2\Modules\Route;

$userController = new Controllers\UserController;

Route::get("/users/id", $userController);

// Route::post("/users/{$id}", $userController);

// Route::patch("/users/{$id}", $userController);

// Route::delete("/users/{$id}", $userController);
