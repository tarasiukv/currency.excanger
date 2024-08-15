<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/exchange-rates', [ExchangeRateController::class, 'index']);
Route::post('/exchange-rates/update', [ExchangeRateController::class, 'update']);
Route::get('/exchange-rates/fetch', [ExchangeRateController::class, 'fetch']);

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('verify', [AuthController::class, 'verify']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);

        Route::patch('password', [AuthController::class, 'changePassword']);
    });
});

Route::middleware('auth:api')->group(function () {
    Route::get('personal-info', [AuthController::class, 'personalInfo']);
});

//Route::middleware('auth')->group(function () {
    Route::post('/transactions', [TransactionController::class, 'store']);
//
//    Route::middleware('role:admin')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index']);
//    });
//
//    Route::middleware('role:client')->group(function () {
//        Route::get('/my-transactions', [TransactionController::class, 'index']);
//    });
//});
