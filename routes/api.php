<?php

use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::post("/register",[UserController::class, "register"]);
Route::post("/login",[UserController::class,"login"]);
Route::middleware(AuthMiddleware::class)->group(function () {
    Route::get("/user/get",[UserController::class,"get"]);
    Route::patch("/user/update",[UserController::class,"update"]);
    Route::delete("/user/logout",[UserController::class,"logout"]);
    
    Route::post("/short/create",[ShortLinkController::class,"createShortLink"]);
    Route::get("/short/show/{idShort}",[ShortLinkController::class,"getShortLink"]);
    Route::get("/short/show",[ShortLinkController::class,"getAllShortLinks"]);
    Route::delete("/short/{idShort}",[ShortLinkController::class,"deleteShortLink"]);
});


Route::get('/{short}', [ShortLinkController::class, 'redirectShortLink']);