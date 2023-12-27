<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(LoginController::class)
    ->group(function () {
        Route::get('login', 'showLoginForm')->name('login');
        Route::post('login', 'login')->name('login.auth');
        Route::post('logout', 'logout')->name('logout');
    });
Route::controller(HomeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
    });

Route::prefix('admin')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::get('dashboard', [DashboardController::class, 'show'])->name('admin.dashboard');
    Route::resource('users', UserController::class)->names('admin.users');
})->name('admin');

Route::view('/test', 'welcome');