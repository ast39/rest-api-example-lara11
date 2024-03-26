<?php

use App\Http\Controllers\Api\V1\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\URL;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::group([], function () {
    Route::apiResource('category', CategoryController::class);
});


Route::fallback(function () {
    return response()->json([
        'message' => 'Endpoint not exist. If error persists, contact alexandr.statut@gmail.com',
    ], 404);
});

if (env('APP_ENV') === 'production') {
    URL::forceScheme('https');
}
