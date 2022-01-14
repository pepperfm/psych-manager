<?php

use App\Http\Controllers\Auth\OAuthController;

Route::prefix('v1')->group(function () {
    Route::post('login', [OAuthController::class, 'issueToken'])->name('login');
    Route::post('logout', [OAuthController::class, 'logout'])->name('logout');
});
