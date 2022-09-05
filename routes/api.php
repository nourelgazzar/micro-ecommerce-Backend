<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('store-category', [CategoryController::class, 'store']);
<<<<<<< HEAD
//(add-new-brand)
Route::apiResource('brands', BrandController::class);
=======

Route::post('store-product', [ProductController::class, 'store']);
>>>>>>> 05e81fe29c6f00dfd0b36b3d1a3f292d48929d0c
