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
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\HallController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ImageController;

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

Route::get('/googleauth', [LoginController::class, 'redirectGoogle'])->name('redirect-google');
Route::get('/googleauth/callback', [LoginController::class, 'callbackGoogle'])->name('callback-google');

Route::prefix('admin/')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::resource('users', UserController::class)->names('users');
    Route::resource('entertainment-venues', EntertainmentVenueController::class)->names('entertainment-venues');

    Route::prefix('entertainment-venues/{entertainmentVenue}/halls/')
        ->name('halls.')
        ->controller(HallController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::put('/{hall}', 'update')->name('update');
            Route::get('/{hall}/edit', 'edit')->name('edit');
            Route::delete('/{hall}', 'destroy')->name('destroy');
        });
    Route::resource('events', EventController::class)->names('events');
    Route::resource('sessions', SessionController::class)->names('sessions');
    // Route::resource('tickets', TicketController::class)->names('tickets');
});
