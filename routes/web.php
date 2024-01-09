<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Admin\Auth\LoginController;
// use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\EntertainmentVenueController;
use App\Http\Controllers\Admin\HallController;

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
// Route::controller(RegisterController::class)
//     ->group(function () {
//         Route::get('register', 'showRegistrationForm')->name('register');
//     });
Route::controller(HomeController::class)
    ->group(function () {
        Route::get('/', 'index')->name('home');
    });

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::resource('users', UserController::class)->names('users');
    Route::resource('entertainment-venues', EntertainmentVenueController::class)->names('entertainment_venues');
    Route::resource('halls', HallController::class)->except(['create', 'edit'])->names('halls');
    Route::get('entertainment-venues/{entertainmentVenue}/halls/create', [HallController::class, 'create'])->name('halls.create');

});

Route::view('/test', 'welcome');
