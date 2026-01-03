<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController; 


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum', 'approved'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    
    Route::get('/apartments', [ApartmentController::class, 'index']);
    Route::get('/apartments/{id}', [ApartmentController::class, 'show']);


    Route::get('/apartments/{id}/ratings', [ApartmentController::class, 'apartmentRatings']);

    
    Route::middleware('role:landlord')->group(function () {
        Route::post('/apartments', [ApartmentController::class, 'store']);
        Route::put('/apartments/{id}', [ApartmentController::class, 'update']);
        Route::delete('/apartments/{id}', [ApartmentController::class, 'destroy']);

        Route::get('/bookings/landlord', [BookingController::class, 'landlordBookings']);
        Route::put('/bookings/{id}/status', [BookingController::class, 'updateStatus']);
    });

    
    Route::middleware('role:tenant')->group(function () {
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/bookings/tenant', [BookingController::class, 'tenantBookings']);
        Route::put('/bookings/{id}', [BookingController::class, 'update']); 
        Route::delete('/bookings/{id}/cancel', [BookingController::class, 'cancel']); 

        Route::get('/favorites', [FavoriteController::class, 'index']);
        Route::post('/favorites', [FavoriteController::class, 'store']);
        Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);

        
        Route::post('/apartments/{id}/rate', [ApartmentController::class, 'rateApartment']);
    });

    
    Route::middleware('roles:tenant,landlord')->group(function () {
        Route::post('/messages', [MessageController::class, 'store']);
        Route::get('/messages/apartment/{id}', [MessageController::class, 'apartmentMessages']);
        Route::get('/messages/my', [MessageController::class, 'myMessages']);
    });

    
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users/pending', [AdminController::class, 'pendingUsers']);
        Route::post('/admin/users/{id}/approve', [AdminController::class, 'approve']);
        Route::post('/admin/users/{id}/reject', [AdminController::class, 'reject']);
        Route::delete('/admin/users/{id}', [AdminController::class, 'destroy']);
    });

    
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
});
