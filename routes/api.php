<?php

use App\Http\Controllers\Api\HotelApiController;
use App\Http\Middleware\HotelApiToken;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('throttle:30,1')->group(function () {
    Route::get('/estado', [HotelApiController::class, 'estado']);

    Route::middleware(HotelApiToken::class)->group(function () {
        Route::get('/habitaciones', [HotelApiController::class, 'habitaciones']);
        Route::get('/habitaciones/{id}', [HotelApiController::class, 'habitacion'])->whereNumber('id');
        Route::get('/reservas', [HotelApiController::class, 'reservas']);
        Route::get('/estadisticas', [HotelApiController::class, 'estadisticas']);
    });
});
