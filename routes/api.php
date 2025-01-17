<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);
Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);


Route::middleware(['jwt.auth','log.activity'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::apiResource('posts', PostController::class);
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{postId}', [PostController::class, 'destroy']);
    // Route::post('/auth/refresh', [AuthController::class, 'refreshToken']);

    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/create', [RoleController::class, 'store']);
        Route::get('/role/{id}', [RoleController::class, 'show']);
        Route::put('/role/{id}', [RoleController::class, 'update']);
        Route::delete('/role/{id}', [RoleController::class, 'destroy']);

    });
});