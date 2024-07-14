<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::controller(AuthController::class)->group(function () {
    // middleware mặc định gọi name 'login' khi !auth
    Route::get('/login', 'unauthorised')->name('login');
    Route::post('/login', 'login')->name('login');
});

Route::prefix('user')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::get('/list', 'list');
            Route::get('/filter', 'filterUsers');
            Route::get('/{id}', 'getUserById');
            Route::post('/create_user', 'createUser')->withoutMiddleware('auth:api');
            Route::patch('/update_user', 'updateUser');
            Route::delete('/delete_user/{id}', 'deleteUser');
        });
    });
});
