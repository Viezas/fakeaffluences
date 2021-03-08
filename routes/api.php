<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/infos', [\App\Http\Controllers\ApiController::class, 'infos'])->name('api.infos');
Route::post('/reservation', [\App\Http\Controllers\ApiController::class, 'booking'])->name('api.booking');
Route::get('/reservation/annulation/{token}', [\App\Http\Controllers\ApiController::class, 'cancelReserv'])->name('api.cancel');
