<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminAuthController;

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
Route::post('/admin-register', [AdminAuthController::class, 'register']);
Route::post('/admin-login', [AdminAuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {
    Route::post('store-category', [CategoryController::class, 'store']);
    Route::post('/admin-logout', [AdminAuthController::class, 'logout']);
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/admin-logout', [AdminAuthController::class, 'logout']);
});

