<?php

use Illuminate\Support\Facades\Route;

use App\Http\Resources\Api\User\UserResource;
use App\Http\Middleware\TransformIndexRequest;

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
], static function () {
    Route::get('/tooltips', [StaticDataController::class, 'getTooltips']);
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
        Route::get('/get-connection-types', [StaticDataController::class, 'getConnectionTypes']);
        Route::get('/get-meeting-types', [StaticDataController::class, 'getMeetingTypes']);
        Route::get('/get-gender-list', [StaticDataController::class, 'getGenderList']);
        Route::get('/get-categories', [StaticDataController::class, 'getCategories']);
        Route::get('/clients-all', [StaticDataController::class, 'getClients']);
    });

    Route::get('user', static fn() => UserResource::make(\Auth::user()));

    Route::get('/calendar-sessions', [SessionController::class, 'getCalendarSessions']);
    Route::post('/users/sync-categories', [UserController::class, 'syncCategories']);

    Route::middleware([TransformIndexRequest::class])->group(static fn() =>
        Route::apiResources([
            'categories' => CategoryController::class,
            'clients' => ClientController::class,
            'sessions' => SessionController::class,
        ])
    );
    Route::apiResource('users', UserController::class)->only(['index', 'update'])
        ->middleware([TransformIndexRequest::class]);
});
