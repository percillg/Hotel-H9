<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\HuespedController;
use App\Http\Controllers\EstadiaController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\PagoServicioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Middleware\HotelAutenticado;
use App\Http\Controllers\UsuarioHotelController;

Route::middleware(HotelAutenticado::class)->group(function () {
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/reservas', [ReservaController::class, 'index'])
    ->name('reservas.index');

Route::get('/reservas/crear', [ReservaController::class, 'create'])
    ->name('reservas.create');

Route::post('/reservas', [ReservaController::class, 'store'])
    ->name('reservas.store');

Route::get('/usuarios', [UsuarioHotelController::class, 'index'])->name('usuarios.index');
Route::get('/usuarios/crear', [UsuarioHotelController::class, 'create'])->name('usuarios.create');
Route::post('/usuarios', [UsuarioHotelController::class, 'store'])->name('usuarios.store');

Route::get('/huespedes', [HuespedController::class, 'index'])
    ->name('huespedes.index');

Route::get('/huespedes/crear', [HuespedController::class, 'create'])
    ->name('huespedes.create');

Route::post('/huespedes', [HuespedController::class, 'store'])
    ->name('huespedes.store');

Route::get('/huespedes/{id}', [HuespedController::class, 'show'])
    ->whereNumber('id')->name('huespedes.show');

Route::get('/huespedes/{id}/editar', [HuespedController::class, 'edit'])
    ->whereNumber('id')->name('huespedes.edit');

Route::put('/huespedes/{id}', [HuespedController::class, 'update'])
    ->whereNumber('id')->name('huespedes.update');

Route::get('/estadias', [EstadiaController::class, 'index'])
    ->name('estadias.index');

Route::post('/estadias/{id}/checkin', [EstadiaController::class, 'checkin'])
    ->name('estadias.checkin');

Route::post('/estadias/{id}/checkout', [EstadiaController::class, 'checkout'])
    ->name('estadias.checkout');
Route::get('/habitaciones', [HabitacionController::class, 'index'])
    ->name('habitaciones.index');

Route::get('/habitaciones/crear', [HabitacionController::class, 'create'])
    ->name('habitaciones.create');

Route::post('/habitaciones', [HabitacionController::class, 'store'])
    ->name('habitaciones.store');

Route::patch('/habitaciones/{id}/estado', [HabitacionController::class, 'cambiarEstado'])
    ->name('habitaciones.estado');

Route::get('/pagos-servicios', [PagoServicioController::class, 'index'])
    ->name('pagos.index');

Route::post('/pagos-servicios/servicio', [PagoServicioController::class, 'agregarServicio'])
    ->name('pagos.servicio');

Route::post('/pagos-servicios/pago', [PagoServicioController::class, 'registrarPago'])
    ->name('pagos.registrar');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/reportes', [ReporteController::class, 'index'])
    ->name('reportes.index');

});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::middleware(HotelAutenticado::class)->get('/configuracion', [ConfiguracionController::class, 'index'])
    ->name('configuracion.index');

Route::middleware(HotelAutenticado::class)->post('/configuracion', [ConfiguracionController::class, 'actualizar'])
    ->name('configuracion.actualizar');