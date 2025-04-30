<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TravelOrdersController;
use App\Http\Controllers\Api\V1\UserController;

Route::apiResource('orders', TravelOrdersController::class);
Route::apiResource('users', UserController::class);
Route::post('/filterOrders', [TravelOrdersController::class, 'showOrdersByFilters']);