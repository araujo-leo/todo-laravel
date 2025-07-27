<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::post('/v1/register', [UserController::class, 'register']);

Route::post('/v1/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/v1/logout', [UserController::class, 'logout']);
});
