<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\EntertainmentVenueController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventGenreController;
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
    ->name('entertainment_venues.')
    ->controller(EntertainmentVenueController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/list', 'getList')->name('get-list');
        Route::get('search', 'search')->name('search');
        Route::get('get-by-city', 'getByCity')->name('getByCity');
    });

Route::prefix('halls')
    ->name('halls.')
    ->controller(HallController::class)
    ->group(function () {
        Route::get('search', 'search')->name('search');
        Route::get('getHallById', 'getHallById')->name('getHallById');
        Route::get('getEnabledHallElements', 'getEnabledHallElements')->name('getEnabledHallElements');
    });

Route::prefix('events')
    ->name('events.')
    ->controller(EventController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{event}', 'show')->name('show');
        Route::middleware('auth:sanctum')->post('/{event}/rate', 'rateEvent')->name('rate');
    });

Route::prefix('sessions')
    ->name('sessions.')
    ->controller(SessionController::class)
    ->group(function () {
        Route::get('find', 'find')->name('find');
    });

Route::prefix('users')
    ->name('users.')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('search', 'search')->name('search');
    });

Route::prefix('tickets')
    ->name('tickets.')
    ->controller(TicketController::class)
    ->group(function () {
        Route::get('get-tickets-count-by-month', 'getTicketsCountByMonth')->name('get_tickets_count_by_month');
    });

Route::prefix('cities')
    ->name('cities.')
    ->controller(CityController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });

Route::prefix('event-genres')
    ->name('eventGenres.')
    ->controller(EventGenreController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')
    ->name('auth.')
    ->controller(AuthController::class)
    ->group(function () {
        Route::middleware('auth:sanctum')->get('/', 'index')->name('index');
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');
        Route::middleware('auth:sanctum')->post('logout', 'logout')->name('logout');
    });
