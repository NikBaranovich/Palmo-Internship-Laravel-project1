<?php

use App\Http\Controllers\Api\EntertainmentVenueController;
use App\Http\Controllers\Api\HallController;
use App\Http\Controllers\Api\SeatGroupController;
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
    });

Route::prefix('seat_groups')
    ->name('api.seat_groups.')
    ->controller(SeatGroupController::class)
    ->group(function () {
        Route::get('search', 'search')->name('search');
    });


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
