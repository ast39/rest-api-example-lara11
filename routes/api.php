<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\URL;


Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:sanctum'], function () {
    Route::apiResource('user', UserController::class);
    Route::apiResource('category', CategoryController::class);
});


//Route::fallback(function () {
//    return response()->json([
//        'message' => 'Endpoint not exist. If error persists, contact alexandr.statut@gmail.com',
//    ], 404);
//});

if (env('APP_ENV') === 'production') {
    URL::forceScheme('https');
}
