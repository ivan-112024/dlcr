<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TripProcessingController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TripsController;

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/get-users', [UserController::class, 'getUsers']);
    Route::post('/add-user', [UserController::class, 'addUser']);
    Route::put('/edit-user/{id}', [UserController::class, 'editUser']);
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);

    // Driver routes
    Route::get('/get-driver', [DriverController::class, 'getDriver']);
    Route::post('/add-driver', [DriverController::class, 'addDriver']);
    Route::put('/edit-driver/{id}', [DriverController::class, 'editDriver']);
    Route::delete('/delete-driver/{id}', [DriverController::class, 'deleteDriver']);

  // Reservation routes
Route::get('/reservations', [ReservationController::class, 'index']);
Route::post('/reservations', [ReservationController::class, 'store']);
Route::get('/reservations/{id}', [ReservationController::class, 'show']); // Note: using {reservation} for route model binding
Route::put('/reservations/{id}', [ReservationController::class, 'update']);
Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

    // Nested    routes under Trips
    Route::prefix('trips/{trip}')->group(function () {
        Route::get('/processings', [TripProcessingController::class, 'indexForTrip']);
        Route::post('/processings', [TripProcessingController::class, 'storeForTrip']);
        Route::get('/processings/{processing}', [TripProcessingController::class, 'showForTrip']);
        Route::put('/processings/{processing}', [TripProcessingController::class, 'updateForTrip']);
        Route::delete('/processings/{processing}', [TripProcessingController::class, 'destroyForTrip']);
    });

    Route::post('/logout', [AuthenticationController::class, 'logout']);
});
