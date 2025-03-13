<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CategoryController;



// Route::prefix('categories')->group(function () {
//     Route::get('/api', [CategoryController::class, 'index']);
//     Route::get('/Parent', [CategoryController::class, 'Parent']);
//     Route::get('/{id}', [CategoryController::class, 'show']);
//     Route::post('/', [CategoryController::class, 'store']);
//     Route::put('/{id}', [CategoryController::class, 'update']);
//     Route::delete('/{id}', [CategoryController::class, 'destroy']);
// });

Route::apiResource('/categories', TagController::class);

// les cours
Route::prefix('courses')->group(function () {
    Route::get('/', [CourseController::class, 'index']);
    Route::get('/{id}', [CourseController::class, 'show']);
    Route::post('/', [CourseController::class, 'store']);
    Route::put('/{id}', [CourseController::class, 'update']);
    Route::delete('/{id}', [CourseController::class, 'destroy']);
});

// les tags
Route::prefix('tags')->group(function () {
    Route::get('/', [TagController::class, 'index']);
    Route::get('/{id}', [TagController::class, 'show']);
    Route::post('/', [TagController::class, 'store']);
    Route::put('/{id}', [TagController::class, 'update']);
    Route::delete('/{id}', [TagController::class, 'destroy']);
});