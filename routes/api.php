<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
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
