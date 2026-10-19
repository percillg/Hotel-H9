<?php

namespace App\Http\Controllers;

use App\Services\HotelMongo;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function __construct(private HotelMongo $mongo)
    {
    }

    public function index(Request $request)
    {
        $reservas = $this->mongo->reservationRows()->filter(function ($reserva) use ($request) {
            if ($request->filled('fecha_entrada') && substr($reserva->fecha_entrada, 0, 10) < $request->fecha_entrada) return false;
            if ($request->filled('fecha_salida') && substr($reserva->fecha_salida, 0, 10) > $request->fecha_salida) return false;
            if ($request->filled('rut') && stripos($reserva->rut, $request->rut) === false) return false;
            if ($request->filled('estado') && $reserva->estado !== $request->estado) return false;
            return true;
        })->values();

        return view('reservas.index', compact('reservas'));
    }

    public function create()
    {
        $huespedes = $this->mongo->all('huespedes', [], ['sort' => ['apellido' => 1, 'nombre' => 1]])
            ->map(fn ($h) => (object) [...(array) $h, 'id_huesped' => $h->_id]);
        $habitaciones = $this->mongo->all('habitaciones', ['estado' => 'Disponible'], ['sort' => ['numero' => 1]])
            ->map(fn ($h) => (object) [...(array) $h, 'id_habitacion' => $h->_id]);

        return view('reservas.create', compact('huespedes', 'habitaciones'));
    }

    public function store(Request $request)
    {
        $fechaMinima = now('America/Santiago')->subDays(7)->toDateString();

        $datos = $request->validate([
            'id_huesped' => ['required', 'integer'],
            'id_habitacion' => ['required', 'integer'],
            'fecha_entrada' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . $fechaMinima],
            'fecha_salida' => ['required', 'date_format:Y-m-d', 'after:fecha_entrada', 'after_or_equal:' . $fechaMinima],
        ], [
            'fecha_entrada.after_or_equal' => 'La fecha de entrada no puede ser anterior a 7 días atrás.',
            'fecha_salida.after_or_equal' => 'La fecha de salida no puede ser anterior a 7 días atrás.',
            'fecha_salida.after' => 'La fecha de salida debe ser posterior a la fecha de entrada.',
            'fecha_entrada.date_format' => 'Ingresa una fecha de entrada válida.',
            'fecha_salida.date_format' => 'Ingresa una fecha de salida válida.',
        ]);

        $huesped = $this->mongo->byId('huespedes', (int) $datos['id_huesped']);
        $habitacion = $this->mongo->byId('habitaciones', (int) $datos['id_habitacion']);

        if (!$huesped || !$habitacion) {
            return back()->withInput()->withErrors(['id_habitacion' => 'Debes seleccionar un huésped y una habitación válidos.']);
        }

        if ($habitacion->estado !== 'Disponible') {
            return back()->withInput()->withErrors(['id_habitacion' => 'La habitación no está disponible en su estado actual.']);
        }

        $conflicto = $this->mongo->count('reservas', [
            'habitaciones.habitacion_id' => $habitacion->_id,
            'estado' => ['$in' => ['Pendiente', 'Confirmada']],
            'fecha_entrada' => ['$lt' => $this->mongo->date($datos['fecha_salida'])],
            'fecha_salida' => ['$gt' => $this->mongo->date($datos['fecha_entrada'])],
        ]) > 0;

        if ($conflicto) {
            return back()->withInput()->withErrors(['id_habitacion' => 'La habitación ya está reservada para ese período.']);
        }

        $id = $this->mongo->nextId('reservas');
        $usuarioId = (int) session('usuario.id_usuario', 1);

        $this->mongo->collection('reservas')->insertOne([
            '_id' => $id,
            'usuario_id' => $usuarioId,
            'huesped' => [
                '_id' => $huesped->_id,
                'rut' => $huesped->rut,
                'nombre' => trim($huesped->nombre . ' ' . $huesped->apellido),
            ],
            'fecha_reserva' => $this->mongo->date(now()->toDateString()),
            'fecha_entrada' => $this->mongo->date($datos['fecha_entrada']),
            'fecha_salida' => $this->mongo->date($datos['fecha_salida']),
            'estado' => 'Confirmada',
            'habitaciones' => [[
                'id_detalle' => $id,
                'habitacion_id' => $habitacion->_id,
                'numero' => $habitacion->numero,
                'tipo' => $habitacion->tipo,
                'precio_noche' => $this->mongo->money($habitacion->precio),
            ]],
            'estadia' => null,
        ]);

        return redirect()->route('reservas.index')->with('success', 'Reserva creada correctamente en MongoDB.');
    }
}
