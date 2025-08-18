<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/login', [UserController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [UserController::class, 'logout']);
        });
    });

    Route::apiResource('tasks', TaskController::class)->only(['index', 'show', 'store', 'update','patch', 'destroy'])->middleware('auth:sanctum');
//    Route::prefix('tasks')->middleware('auth:sanctum')->group(function () {
//        Route::post('/', [App\Http\Controllers\TaskController::class, 'create']);
//        Route::get('/', [App\Http\Controllers\TaskController::class, 'index']);
//        Route::get('/{task}', [App\Http\Controllers\TaskController::class, 'show']);
//        Route::delete('/{task}', [App\Http\Controllers\TaskController::class, 'destroy']);
//        Route::put('/{task}', [App\Http\Controllers\TaskController::class, 'update']);
//        Route::patch('/{task}', [App\Http\Controllers\TaskController::class, 'patch']);
//    });
});
