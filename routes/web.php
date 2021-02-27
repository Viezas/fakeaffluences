<?php

use Config\information;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('index');

Route::get('/reservation', [\App\Http\Controllers\ReservationController::class, 'view'])->name('reservation');
Route::post('/reservation', [\App\Http\Controllers\ReservationController::class, 'reserv',])->name('reservation.post');
Route::get('/reservation/annulation/{token}', [\App\Http\Controllers\ReservationController::class, 'cancelReserv'])->name('cancel.reservation');