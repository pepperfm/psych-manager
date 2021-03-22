<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\OAuthController;

use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\SessionController;
use App\Http\Controllers\Api\Admin\DoctorController;

use App\Http\Controllers\Api\Admin\StaticDataController;

/**
 * Unauthorized
 */
Route::group([
    'prefix' => 'v1',
], function () {
    Route::post('/auth', [AuthController::class, 'auth'])->name('auth');

    Route::post('passport-login', [OAuthController::class, 'issueToken'])->name('passport-login');
    Route::post('passport-logout', [OAuthController::class, 'logout'])->middleware('auth:api')->name('passport-logout');

    Route::get('/tooltips', [StaticDataController::class, 'getTooltips']);
});

/**
 * Authorized
 */
Route::group([
    'prefix' => 'v1',
    'middleware' => 'auth:api',
], function () {
    Route::group([
        'prefix' => 'static-data'
    ], function () {
        Route::get('/get-connection-types', [StaticDataController::class, 'getConnectionTypes']);
        Route::get('/get-meeting-types', [StaticDataController::class, 'getMeetingTypes']);
        Route::get('/get-gender-list', [StaticDataController::class, 'getGenderList']);
        Route::get('/get-categories', [StaticDataController::class, 'getCategories']);
        Route::get('/users-all', [StaticDataController::class, 'getUsers']);
    });
    
    Route::get('/calendar-sessions', [SessionController::class, 'getCalendarSessions']);
    Route::post('/doctors/sync-categories', [DoctorController::class, 'syncCategories']);

    Route::apiResources([
        'categories' => CategoryController::class,
        'users' => UserController::class,
        'sessions' => SessionController::class,
    ]);
    Route::apiResource('doctors', DoctorController::class)->only(['index', 'update']);
});
