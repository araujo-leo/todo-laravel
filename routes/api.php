<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;



Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/login', [UserController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [UserController::class, 'logout']);
        });
    });

    Route::prefix('tasks')->middleware('auth:sanctum')->group(function () {
        Route::post('/', [App\Http\Controllers\TaskController::class, 'create']);
        Route::get('/', [App\Http\Controllers\TaskController::class, 'index']);
    });
});
