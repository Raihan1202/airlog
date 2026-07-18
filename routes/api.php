<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\LogbookItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UnitController;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);

        Route::get('/logbooks-statistics', [LogbookController::class, 'statistics']);
        
        Route::get('/logbooks', [LogbookController::class, 'apiIndex']);
        Route::post('/units/{unit_id}/logbooks', [LogbookController::class, 'store']);
        Route::put('/units/{unit_id}/logbooks/{logbook_id}', [LogbookController::class, 'update']);
        Route::delete('/units/{unit_id}/logbooks/{logbook_id}', [LogbookController::class, 'destroy']);
        Route::post('/units/{unit_id}/logbooks/{logbook_id}/approve', [LogbookController::class, 'approve']);
        
        Route::get('/units/{unit_id}/logbooks/{logbook_id}/view', [LogbookController::class, 'show']);

        Route::prefix('units/{unit_id}/logbooks/{logbook_id}')->group(function () {
            Route::get('/items', [LogbookItemController::class, 'index']);      
            Route::post('/items', [LogbookItemController::class, 'store']);     
            Route::put('/items/{item_id}', [LogbookItemController::class, 'update']);   
            Route::delete('/items/{item_id}', [LogbookItemController::class, 'destroy']); 
        });

        Route::get('/logbook-items/by-teknisi', [LogbookItemController::class, 'getByTeknisi']);
        Route::get('/teknisi-summary', [LogbookItemController::class, 'teknisiSummary']);

        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']); 
        Route::get('/users/{id}', [UserController::class, 'edit']); 
        Route::put('/users/{id}', [UserController::class, 'update']); 
        Route::delete('/users/{id}', [UserController::class, 'destroy']); 
        
        Route::get('/technicians', [UserController::class, 'technicians']);
        Route::get('/positions', [UserController::class, 'positions']);

        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index']);
            Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
            Route::post('/', [NotificationController::class, 'store']);
            Route::get('/{id}/read', [NotificationController::class, 'markAsRead']);
            Route::get('/read-all', [NotificationController::class, 'markAllAsRead']);
            Route::delete('/{id}', [NotificationController::class, 'destroy']);
        });

        Route::get('/tools', [ToolController::class, 'index']);
        Route::post('/tools/save', [ToolController::class, 'update']);
        Route::post('/tools/delete', [ToolController::class, 'delete']);

        Route::get('/positions-list', [PositionController::class, 'index']);
        Route::post('/positions/save', [PositionController::class, 'update']); 
        Route::post('/positions/delete', [PositionController::class, 'delete']);
        
        Route::apiResource('units', UnitController::class);

        Route::any('units//logbooks/{any?}', function() {
             return response()->json(['success' => false, 'message' => 'Unit ID wajib diisi. Format: /units/{unit_id}/logbooks'], 400);
        })->where('any', '.*');

        Route::match(['put', 'patch', 'delete'], 'units/logbooks', function() {
            return response()->json(['success' => false, 'message' => 'Unit ID wajib diisi. Format: /units/{unit_id}/logbooks'], 400);
        });

        Route::any('units/{unit_id}/logbooks//items/{any?}', function() {
             return response()->json(['success' => false, 'message' => 'Logbook ID wajib diisi. Format: .../logbooks/{logbook_id}/items'], 400);
        })->where('any', '.*');

        Route::any('units/{unit_id}/logbooks//approve', function() {
             return response()->json(['success' => false, 'message' => 'Logbook ID wajib diisi sebelum /approve.'], 400);
        });

        Route::any('units/{unit_id}/logbooks/approve', function() {
             return response()->json(['success' => false, 'message' => 'Logbook ID wajib diisi sebelum /approve.'], 400);
        });

        Route::match(['put', 'patch', 'delete'], 'units/{unit_id}/logbooks/{logbook_id}/items', function() {
            return response()->json(['success' => false, 'message' => 'Item ID wajib diisi untuk edit/hapus. Format: .../items/{item_id}'], 400);
        });

        Route::match(['put', 'patch', 'delete'], 'units/{unit_id}/logbooks', function($unit_id) {
            return response()->json(['success' => false, 'message' => 'Logbook ID wajib diisi. Format: /units/'.$unit_id.'/logbooks/{logbook_id}'], 400);
        });

        Route::match(['delete', 'patch', 'put'], 'notifications', function() {
            return response()->json(['success' => false, 'message' => 'Notification ID wajib diisi. Format: /notifications/{id}'], 400);
        });
        
        Route::any('notifications/read', function() {
            return response()->json(['success' => false, 'message' => 'Notification ID wajib diisi. Format: /notifications/{id}/read'], 400);
        });

        Route::match(['put', 'patch', 'delete'], 'units', function() {
            return response()->json(['success' => false, 'message' => 'Unit ID wajib diisi: /units/{id}'], 400);
        });
        
        Route::match(['put', 'patch', 'delete'], 'users', function() {
            return response()->json(['success' => false, 'message' => 'User ID wajib diisi: /users/{id}'], 400);
        });

        Route::any('units/logbooks/{any?}', function() {
            return response()->json(['success' => false, 'message' => 'Unit ID wajib diisi. Format: /units/{unit_id}/logbooks'], 400);
        })->where('any', '.*');

        Route::fallback(function(){
            return response()->json([
                'success' => false,
                'message' => 'Endpoint API tidak ditemukan (404). Periksa URL dan Parameter ID Anda.'
            ], 404);
        });
    });
});