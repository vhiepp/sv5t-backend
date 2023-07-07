<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\PostController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

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

        Route::prefix('post')->group(function () {

            Route::prefix('blogs')->group(function () {

                Route::post('create', [\App\Http\Controllers\Admin\BlogController::class, 'store']);

            });

            Route::prefix('notification')->group(function () {

                Route::post('create', [\App\Http\Controllers\Admin\NotificationController::class, 'store']);

            });

        });

        

    });
    // end admin api
    
    Route::post('posts', [PostController::class, 'get']);
    Route::post('post', [PostController::class, 'getBySlug']);

    // get blogs list 
    Route::post('blogs', [BlogController::class, 'getList']);
    Route::post('blog', [BlogController::class, 'getBySlug']);


    Route::post('upload', [\App\Http\Controllers\Admin\FileController::class, 'upload']);
    Route::delete('upload', [\App\Http\Controllers\Admin\FileController::class, 'delete']);
    

    
});