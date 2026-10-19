<?php

namespace App\Http\Controllers;

use App\Services\HotelMongo;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function __construct(private HotelMongo $mongo)
    {
    }

    public function index(Request $request)
    {
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        $reservas = $this->mongo->reservationRows()->filter(function ($reserva) use ($fechaInicio, $fechaFin) {
            $fecha = substr($reserva->fecha_entrada, 0, 10);
            return (!$fechaInicio || $fecha >= $fechaInicio) && (!$fechaFin || $fecha <= $fechaFin);
        })->sortByDesc('fecha_entrada')->values();

        $pagos = $this->mongo->all('pagos', ['estado' => 'Pagado']);
        if ($fechaInicio) $pagos = $pagos->filter(fn ($p) => substr($p->fecha_pago, 0, 10) >= $fechaInicio);
        if ($fechaFin) $pagos = $pagos->filter(fn ($p) => substr($p->fecha_pago, 0, 10) <= $fechaFin);

        $ingresos = $pagos->sum('monto');
        $totalReservas = $reservas->unique('id_reserva')->count();
        $huespedes = $reservas->unique('rut')->count();
        $reservasFinalizadas = $reservas->where('estado', 'Finalizada')->unique('id_reserva')->count();

        return view('reportes.index', compact(
            'reservas', 'ingresos', 'totalReservas', 'huespedes', 'reservasFinalizadas',
            'fechaInicio', 'fechaFin'
        ));
    }
}
