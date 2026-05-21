<?php

use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//    return $request->user();
// })->middleware('auth:sanctum');

// API/BRAND CONTROLLER
Route::prefix('brands')->group(function () {
    Route::get('trashed', [BrandController::class, 'trashed']);
    Route::get('', [BrandController::class, 'index']);
    Route::post('', [BrandController::class, 'store']);
    Route::get('{id}',[BrandController::class,'show']);
    Route::put('',[BrandController::class,'update']);
    Route::put('{id}',[BrandController::class,'update']);
    Route::patch('',[BrandController::class,'update']);
    Route::patch('{id}',[BrandController::class,'update']);
    Route::delete('', [BrandController::class, 'destroy']);
    Route::delete('{id}',[BrandController::class, 'destroy']);
    Route::patch('{id}/restore',[BrandController::class,'restore']);
    Route::delete('{id}/forceDestroy', [BrandController::class, 'forceDestroy']);

});

Route::prefix('countries')->group(function () {
    Route::get('', [CountryController::class, 'index']);
});

Route::prefix('categories')->group (function () {
   Route::get('', [CategoryController::class, 'index']);
});
