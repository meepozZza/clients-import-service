<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('clients', ClientController::class)->only([
        'index',
        'import',
    ]);

    Route::post('clients/import', [ClientController::class, 'import']);
});
