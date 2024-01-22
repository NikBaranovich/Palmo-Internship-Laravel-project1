<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EntertainmentVenueController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\HallController;
use App\Http\Controllers\Api\SeatGroupController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\UserController;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::prefix('entertainment_venues')
    ->name('api.entertainment_venues.')
    ->controller(EntertainmentVenueController::class)
    ->group(function () {
        Route::get('search', 'search')->name('search');
    });

Route::prefix('halls')
    ->name('api.halls.')
    ->controller(HallController::class)
    ->group(function () {
        Route::get('search', 'search')->name('search');
        Route::get('getHallById', 'getHallById')->name('getHallById');
        Route::get('getEnabledHallElements', 'getEnabledHallElements')->name('getEnabledHallElements');
    });

Route::prefix('events')
    ->name('api.events.')
    ->controller(EventController::class)
    ->group(function () {
        Route::get('search', 'search')->name('search');
    });

Route::prefix('events')
    ->name('api.events.')
    ->controller(EventController::class)
    ->group(function () {
        Route::get('search', 'search')->name('search');
        Route::get('get-top', 'getTop')->name('get_top');
    });

Route::prefix('sessions')
    ->name('api.sessions.')
    ->controller(SessionController::class)
    ->group(function () {
        Route::get('find', 'find')->name('find');
    });

Route::prefix('users')
    ->name('api.users.')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('search', 'search')->name('search');
    });

Route::prefix('tickets')
    ->name('api.tickets.')
    ->controller(TicketController::class)
    ->group(function () {
        Route::get('get-tickets-count-by-month', 'getTicketsCountByMonth')->name('get_tickets_count_by_month');
    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')
    ->name('api.auth.')
    ->controller(AuthController::class)
    ->group(function () {
        Route::get('register', 'register')->name('register');
        Route::get('login', 'login')->name('login');
    });
