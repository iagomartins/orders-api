<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TravelOrdersController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\UserNotificationsController;

Route::post('/authenticate', [UserController::class, 'createAccessToken']);
Route::group(['prefix'=> 'v1', 'middleware' => 'auth:sanctum'], function () {
    Route::apiResource('orders', TravelOrdersController::class);
    Route::apiResource('users', UserController::class);
    Route::post('/userLogin', [UserController::class, 'login']);
    Route::post('/filterOrders', [TravelOrdersController::class, 'showOrdersByFilters']);
    Route::post('/ordersByUser', [TravelOrdersController::class, 'showOrdersByUser']);
    Route::apiResource('notifications', UserNotificationsController::class);
    Route::post('/showUserNotifications', [UserNotificationsController::class, 'getNotificationsByUser']);
});