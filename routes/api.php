<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// dd('here');
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::put('users/info', [AuthController::class, 'updateInfo']);
    Route::put('users/password', [AuthController::class, 'updatePassword']);
    Route::apiResource('users', UserController::class);

    Route::get('admin', [AuthController::class, 'authenticated'])->middleware('scope:admin');
    Route::get('influencer', [AuthController::class, 'authenticated'])->middleware('scope:influencer');
    Route::get('scope/{scope}', [AuthController::class, 'scopeCan']);
});

// Route::get('users', [UserController::class, 'index']);
// Route::get('users/{id}', [UserController::class, 'show']);
