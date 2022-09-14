<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;

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

Route::post('/admin/register', [AdminAuthController::class, 'register']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::post('/user/register', [UserAuthController::class, 'register']);
Route::post('/user/login', [UserAuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
    Route::post('/user/logout', [UserAuthController::class, 'logout']);
    Route::post('/orders', [OrderController::class, 'create']);
    Route::delete('/orders/{id}', [OrderController::class, 'delete']);
    Route::get('/orders/{user_id}', [OrderController::class, 'show']);
    Route::group(['prefix' => 'user/carts/'], function () {
        Route::post('add', [CartController::class, 'add']);
        Route::delete('delete/{product_id}', [CartController::class, 'delete']);
        Route::put('edit', [CartController::class, 'edit']);
        Route::get('{cart_id}', [CartController::class, 'show']);
        Route::delete('clear/{cart_id}', [CartController::class, 'clear']);
    });
});

Route::group(['prefix' => 'admin/', 'middleware' => ['auth:sanctum', 'role:admin']], function () {
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('categories/search/{name}', [CategoryController::class, 'search']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

    Route::group(
        ['prefix' => 'products/'],
        function () {
            Route::post('filter', [ProductController::class, 'filter_and_search']);
            Route::post('', [ProductController::class, 'store']);
            Route::get('', [ProductController::class, 'index']);
            Route::post('{id}', [ProductController::class, 'show']);
            Route::delete('{id}', [ProductController::class, 'delete']);
            Route::put('{id}', [ProductController::class, 'update']);
        }
    );
    Route::apiResource('brands', BrandController::class);
    Route::get('brands/search/{name}', [BrandController::class, 'search']);
});
