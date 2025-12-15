<?php

use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;


Route::get('/', [ReservationController::class, 'index']);
Route::get('/reservation', [ReservationController::class, 'reservation']);