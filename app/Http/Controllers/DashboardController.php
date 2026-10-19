<?php

namespace App\Http\Controllers;

use App\Services\HotelMongo;

class DashboardController extends Controller
{
    public function __construct(private HotelMongo $mongo)
    {
    }

    public function index()
    {
        $totalHabitaciones = $this->mongo->count('habitaciones');
        $habitacionesDisponibles = $this->mongo->count('habitaciones', ['estado' => 'Disponible']);
        $habitacionesOcupadas = $this->mongo->count('habitaciones', ['estado' => 'Ocupada']);
        $habitacionesLimpieza = $this->mongo->count('habitaciones', ['estado' => 'En limpieza']);
        $reservasConfirmadas = $this->mongo->count('reservas', ['estado' => 'Confirmada']);
        $huespedesRegistrados = $this->mongo->count('huespedes');
        $estadiasActivas = $this->mongo->count('reservas', ['estadia.estado' => 'Activa']);
        $ingresos = $this->mongo->all('pagos', ['estado' => 'Pagado'])->sum('monto');
        $ocupacion = $totalHabitaciones > 0 ? round($habitacionesOcupadas * 100 / $totalHabitaciones) : 0;
        $proximasReservas = $this->mongo->reservationRows()->where('estado', 'Confirmada')->take(5);

        return view('dashboard.index', compact(
            'totalHabitaciones', 'habitacionesDisponibles', 'habitacionesOcupadas',
            'habitacionesLimpieza', 'reservasConfirmadas', 'huespedesRegistrados',
            'estadiasActivas', 'ingresos', 'ocupacion', 'proximasReservas'
        ));
    }
}
