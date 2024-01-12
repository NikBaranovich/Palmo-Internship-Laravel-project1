<?php

use App\Http\Controllers\Admin\EntertainmentVenueController;
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
        Route::get('search', 'search')->name('search');
    });

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
