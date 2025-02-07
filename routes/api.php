<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::post("/register",[UserController::class, "register"]);
Route::post("/login",[UserController::class,"login"]);
Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get("/user/get",[UserController::class,"get"]);
    Route::patch("/user/update",[UserController::class,"update"]);
    Route::delete("/user/logout",[UserController::class,"logout"]);
});