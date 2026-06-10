<?php

use Illuminate\Support\Facades\Route;

// API Root Status Route
Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Manna API is fully online and ready!',
        'version' => '1.0.0',
        'database' => 'connected'
    ]);
});

// Public routes
Route::post('/auth/login', [App\Http\Controllers\Api\Auth\AuthController::class, 'login']);
Route::post('/auth/register', [App\Http\Controllers\Api\Auth\AuthController::class, 'register']);
Route::post('/auth/forgot-password', [App\Http\Controllers\Api\Auth\AuthController::class, 'forgotPassword']);

// Public property listing
Route::get('/properties', [App\Http\Controllers\Api\Property\PropertyController::class, 'index']);
Route::get('/properties/featured', [App\Http\Controllers\Api\Property\PropertyController::class, 'featured']);
Route::get('/properties/search', [App\Http\Controllers\Api\Property\PropertyController::class, 'search']);
Route::get('/properties/{id}', [App\Http\Controllers\Api\Property\PropertyController::class, 'show']);

// Protected routes (require auth)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [App\Http\Controllers\Api\Auth\AuthController::class, 'logout']);
    Route::get('/auth/user', [App\Http\Controllers\Api\Auth\AuthController::class, 'user']);
    Route::post('/auth/profile', [App\Http\Controllers\Api\Auth\AuthController::class, 'updateProfile']);

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Api\Dashboard\DashboardController::class, 'index']);

    // Properties (authenticated)
    Route::post('/properties', [App\Http\Controllers\Api\Property\PropertyController::class, 'store']);
    Route::put('/properties/{id}', [App\Http\Controllers\Api\Property\PropertyController::class, 'update']);
    Route::delete('/properties/{id}', [App\Http\Controllers\Api\Property\PropertyController::class, 'destroy']);

    // Favorites
    Route::post('/properties/{id}/favorite', [App\Http\Controllers\Api\Property\PropertyController::class, 'toggleFavorite']);
    Route::get('/favorites', [App\Http\Controllers\Api\Property\PropertyController::class, 'favorites']);

    // Applications
    Route::get('/applications', [App\Http\Controllers\Api\Property\PropertyController::class, 'applications']);
    Route::post('/properties/{id}/apply', [App\Http\Controllers\Api\Property\PropertyController::class, 'apply']);

    // Leases
    Route::get('/leases', [App\Http\Controllers\Api\Lease\LeaseController::class, 'index']);
    Route::get('/leases/{id}', [App\Http\Controllers\Api\Lease\LeaseController::class, 'show']);
    Route::post('/leases', [App\Http\Controllers\Api\Lease\LeaseController::class, 'store']);
    Route::put('/leases/{id}', [App\Http\Controllers\Api\Lease\LeaseController::class, 'update']);

    // Payments
    Route::get('/payments', [App\Http\Controllers\Api\Payment\PaymentController::class, 'index']);
    Route::get('/payments/history', [App\Http\Controllers\Api\Payment\PaymentController::class, 'history']);
    Route::post('/payments/make', [App\Http\Controllers\Api\Payment\PaymentController::class, 'makePayment']);

    // Maintenance
    Route::get('/maintenance', [App\Http\Controllers\Api\Maintenance\MaintenanceController::class, 'index']);
    Route::post('/maintenance', [App\Http\Controllers\Api\Maintenance\MaintenanceController::class, 'store']);
    Route::put('/maintenance/{id}/status', [App\Http\Controllers\Api\Maintenance\MaintenanceController::class, 'updateStatus']);

    // Messages
    Route::get('/messages', [App\Http\Controllers\Api\Message\MessageController::class, 'index']);
    Route::post('/messages', [App\Http\Controllers\Api\Message\MessageController::class, 'store']);
    Route::put('/messages/{id}/read', [App\Http\Controllers\Api\Message\MessageController::class, 'markAsRead']);

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Api\Notification\NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [App\Http\Controllers\Api\Notification\NotificationController::class, 'unreadCount']);
    Route::put('/notifications/{id}/read', [App\Http\Controllers\Api\Notification\NotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\Api\Notification\NotificationController::class, 'markAllAsRead']);

    // Admin/Super Admin routes
    Route::middleware('role:super_admin,admin')->group(function () {
        Route::get('/users', [App\Http\Controllers\Api\User\UserController::class, 'index']);
        Route::get('/users/{id}', [App\Http\Controllers\Api\User\UserController::class, 'show']);
        Route::put('/users/{id}/role', [App\Http\Controllers\Api\User\UserController::class, 'updateRole']);
        Route::post('/users/{id}/suspend', [App\Http\Controllers\Api\User\UserController::class, 'suspend']);
    });
});
