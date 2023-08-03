<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('api')->group(function () {

    // admin api
    Route::prefix('admin')->group(function () {

        Route::prefix('auth')->group(function () {

            Route::post('login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);
            
            Route::post('google/url', [\App\Http\Controllers\Admin\AuthController::class, 'getGoogleUrl']);
            Route::post('google/login', [\App\Http\Controllers\Admin\AuthController::class, 'googleLogin']);
            
            Route::middleware('auth.admin.login')->group(function () {
        
                Route::post('logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout']);
                Route::post('refresh', [\App\Http\Controllers\Admin\AuthController::class, 'refresh']);
                Route::post('me', [\App\Http\Controllers\Admin\AuthController::class, 'me']);
        
            });

        });
        Route::middleware('auth.admin.login')->group(function () {
            Route::prefix('forum')->group(function () {
    
                Route::prefix('posts')->group(function () {
    
                    Route::post('create', [\App\Http\Controllers\Admin\PostController::class, 'store']);
                    Route::post('active', [\App\Http\Controllers\Admin\PostController::class, 'active']);
                    Route::post('update', [\App\Http\Controllers\Admin\PostController::class, 'update']);
                    Route::post('delete', [\App\Http\Controllers\Admin\PostController::class, 'destroy']);
    
                    Route::post('pending-count', [\App\Http\Controllers\Admin\PostController::class, 'pendingCout']);
    
                });
    
                Route::prefix('notifications')->group(function () {
    
                    Route::post('create', [\App\Http\Controllers\Admin\NotificationController::class, 'store']);
                    Route::post('active', [\App\Http\Controllers\Admin\NotificationController::class, 'active']);
                    Route::post('update', [\App\Http\Controllers\Admin\NotificationController::class, 'update']);
                    Route::post('delete', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy']);
    
                    Route::post('pending-count', [\App\Http\Controllers\Admin\NotificationController::class, 'pendingCout']);
    
                });
    
            });

            Route::prefix('approval')->group(function () {
                Route::post('create', [\App\Http\Controllers\Admin\ApprovalController::class, 'store']);
                Route::post('get', [\App\Http\Controllers\Admin\ApprovalController::class, 'index']);
            });
        });

        

    });
    // end admin api
    
    // get post list 
    Route::post('posts', [PostController::class, 'getList']);
    Route::post('post', [PostController::class, 'getBySlug']);

    // get notification list 
    Route::post('notifications', [NotificationController::class, 'getList']);
    Route::post('notification', [NotificationController::class, 'getBySlug']);

    Route::post('upload', [\App\Http\Controllers\Admin\FileController::class, 'upload']);
    Route::delete('upload', [\App\Http\Controllers\Admin\FileController::class, 'delete']);

    Route::prefix('authen')->group(function () {
        Route::post('signin', [AuthController::class, 'signin']);

        Route::prefix('microsoft')->group(function () {
            Route::post('url', [AuthController::class, 'microsoftGetUrl']);
            Route::post('callback', [AuthController::class, 'microsoftSignin']);
        });

        Route::middleware('auth.signin')->group(function () {
            Route::post('user', [AuthController::class, 'user']);
            Route::post('signout', [AuthController::class, 'signout']);
        });

    });
    
});