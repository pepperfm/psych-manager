<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', static fn () => redirect('/panel'));
Route::view('/panel', 'app');
Route::view('/panel/{any}', 'app')->where('any', '.*');

// Route::get('/', function () {
//     return view('welcome');
// });
//
// Auth::routes([
//     'login'    => true,
//     'logout'   => true,
//     'register' => false,
//     'reset'    => false,
//     'confirm'  => false,
//     'verify'   => false,
// ]);
//
// Route::get('/register', function () {
//     return redirect('/');
// });
//
// Route::prefix('admin')->group(function () {
//     Route::get('/login', [LoginController::class, 'showLoginForm']);
//     Route::post('/login', [LoginController::class, 'login'])->name('admin-login');
//     Route::post('/logout', [LoginController::class, 'logout'])->name('admin-logout');
//
//     Route::group([
//         // 'middleware' => 'auth:api'
//         'middleware' => 'auth'
//     ], function () {
//         Route::view('/', 'admin.main.index')->name('admin');
//
//         Route::view('sessions', 'admin.sessions.index');
//         Route::view('users', 'admin.users.index');
//         Route::view('categories', 'admin.categories.index');
//         Route::view('profile', 'admin.profile')->name('profile');
//     });
// });
