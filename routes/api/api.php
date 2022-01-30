<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\TransformIndexRequest;

use App\Http\Controllers\Api\{
    CategoryController,
    ClientController,
    SessionController,
    UserController,
    UserFilterController
};

use App\Http\Controllers\Api\StaticDataController;

/**
 * Unauthorized
 */
Route::group([
    'prefix' => 'v1',
], static function () {
    Route::get('tooltips', [StaticDataController::class, 'getTooltips']);
});

/**
 * Authorized
 */
Route::group([
    'prefix' => 'v1',
    'middleware' => 'auth:api',
], static function () {
    Route::group([
        'prefix' => 'static-data'
    ], static function () {
        Route::get('connection-types', [StaticDataController::class, 'getConnectionTypes']);
        Route::get('meeting-types', [StaticDataController::class, 'getMeetingTypes']);
        Route::get('gender-list', [StaticDataController::class, 'getGenderList']);
        Route::get('categories', [StaticDataController::class, 'getCategories']);
    });

    Route::group([
        'prefix' => 'filters',
        'middleware' => 'throttle:60,1'
    ], static function() {
        Route::get('/{module}', [UserFilterController::class, 'get']);
        Route::post('/{module}', [UserFilterController::class, 'set']);
        Route::get('/clear/{module?}', [UserFilterController::class, 'clear']);
    });

    Route::get('session-clients', [ClientController::class, 'getSessionClients']);
    Route::get('calendar-sessions', [SessionController::class, 'getCalendarSessions']);
    Route::post('users/sync-categories', [UserController::class, 'syncCategories']);

    Route::middleware([TransformIndexRequest::class])->group(static fn() =>
        Route::apiResources([
            'categories' => CategoryController::class,
            'clients' => ClientController::class,
            'sessions' => SessionController::class,
        ])
    );
    Route::apiResource('users', UserController::class)->only(['index', 'update']);
});
