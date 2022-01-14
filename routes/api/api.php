<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\{
    CategoryController,
    ClientController,
    SessionController,
    UserController
};

use App\Http\Controllers\Api\StaticDataController;

/**
 * Unauthorized
 */
Route::group([
    'prefix' => 'v1',
], function () {
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
        Route::get('/clients-all', [StaticDataController::class, 'getClients']);
    });

    Route::get('user', fn() => \Auth::user());

    Route::get('/calendar-sessions', [SessionController::class, 'getCalendarSessions']);
    Route::post('/users/sync-categories', [UserController::class, 'syncCategories']);

    Route::apiResources([
        'categories' => CategoryController::class,
        'clients' => ClientController::class,
        'sessions' => SessionController::class,
    ]);
    Route::apiResource('users', UserController::class)->only(['index', 'update']);
});
