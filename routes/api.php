<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Exchange rate
Route::get('/exchange-rates', [ExchangeRateController::class, 'index']);
Route::middleware('auth:api')->group(function () {
    Route::post('/exchange-rates/update', [ExchangeRateController::class, 'update']);
    Route::get('/exchange-rates/fetch', [ExchangeRateController::class, 'fetch']);
});

// Transactions
Route::middleware('auth:api')->group(function () {
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('transactions/{transaction}', [TransactionController::class, 'show']);
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::put('transactions/{transaction}', [TransactionController::class, 'update']);
    Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy']);
    Route::post('transactions/search', [TransactionController::class, 'search']);
});

// Profile
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

/**
 * Auth prefix group
 */
Route::group(['prefix' => 'auth'], function () {

    Route::middleware('auth:api')->group(function () {
        Route::post('verify', [AuthController::class, 'verify']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::patch('password', [AuthController::class, 'changePassword']);
        Route::get('me', [AuthController::class, 'me']);
    });
});
