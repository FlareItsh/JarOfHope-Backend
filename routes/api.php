<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\UserController;

// Route::middleware('auth:sanctum')->group(function () {
//     Route::delete('/logout', [AuthController::class, 'logout']);
//     Route::get('orders/summary', OrderSummaryController::class);

//     Route::apiResources([
//         'companies' => CompanyController::class,
//         'languages' => LanguageController::class,
//         'customers' => CustomerController::class,
//         'orders' => OrderController::class,
//         'order-items' => OrderItemController::class,
//         'products' => ProductController::class,
//     ]);
// });

Route::post('/login', [AuthController::class, 'login']);
Route::get('/generate-nickname', [AuthController::class, 'generateNickname']);
Route::post('/messages', [MessageController::class, 'store'])->middleware('throttle:message_sending');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/superadmin/stats', function(Request $request) {
        if ($request->user()->role !== 'superadmin') abort(403);
        return response()->json([
            'total_users' => \App\Models\User::count(),
            'total_messages' => \App\Models\Message::whereNull('parent_uuid')->count()
        ]);
    });
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [App\Http\Controllers\UserController::class, 'updateProfile']);

    Route::apiResource('messages', MessageController::class)->except(['store']);
    Route::apiResource('attachments', AttachmentController::class);
    Route::apiResource('users', UserController::class);
});
