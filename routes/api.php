<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\MentorController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\UserProfileController;



Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});

// les cours
Route::prefix('courses')->middleware('auth:sanctum')->group(function () {
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

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});

// Statistics Routes
Route::prefix('stats')->group(function () {
    // Course Statistics
    Route::get('/courses', [StatisticsController::class, 'coursesStatistics'])
        ->middleware(['auth:sanctum']);

    // Category Statistics
    Route::get('/categories', [StatisticsController::class, 'categoriesStatistics'])
        ->middleware(['auth:sanctum',]);

    // Tag Statistics
    Route::get('/tags', [StatisticsController::class, 'tagsStatistics'])
        ->middleware(['auth:sanctum',]);
});

Route::prefix('roles')->middleware(['auth:sanctum'])->group(function () {
    // List all roles
    Route::get('/', [RoleController::class, 'index']);

    // Create a new role
    Route::post('/', [RoleController::class, 'store']);

    Route::put('/{id}', [RoleController::class, 'update']);

    // Delete a role
    Route::delete('/{id}', [RoleController::class, 'destroy']);
});

// User Profile Routes
Route::prefix('users')->middleware('auth:sanctum')->group(function () {
    // Get user profile
    Route::get('/{id}', [UserProfileController::class, 'show'])
        ->middleware(['auth:sanctum']);

    Route::patch('/{id}', [UserProfileController::class, 'update'])
        ->middleware('auth:sanctum');

    Route::delete('/{id}', [UserProfileController::class, 'destroy'])
        ->middleware('auth:sanctum');
});

// Mentor Space Routes
Route::prefix('mentors')->middleware('auth:sanctum')->group(function () {
    // List courses created by a mentor
    Route::get('/{id}/courses', [MentorController::class, 'listCourses']);

    // List students enrolled in mentor's courses
    Route::get('/{id}/students', [MentorController::class, 'listStudents']);

    // Get mentor performance statistics
    Route::get('/{id}/performance', [MentorController::class, 'getPerformance']);
});

// Student Space Routes
Route::prefix('students')->middleware('auth:sanctum')->group(function () {
    // List courses enrolled by a student
    Route::get('/{id}/courses', [StudentController::class, 'listCourses']);

    // Get student course progress
    Route::get('/{id}/progress', [StudentController::class, 'getProgress']);

    // Get student badges
    Route::get('/{id}/badges', [StudentController::class, 'getBadges']);
});