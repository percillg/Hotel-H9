<?php

namespace App\Http\Controllers;

use App\Services\HotelMongo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EstadiaController extends Controller
{
    public function __construct(private HotelMongo $mongo)
    {
    }

    public function index(Request $request)
    {
        $reservas = $this->mongo->reservationRows()
            ->filter(fn ($reserva) => $reserva->estado_reserva === 'Confirmada' || $reserva->estado_estadia === 'Activa');

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $reservas = $reservas->filter(fn ($r) =>
                stripos($r->nombre, $buscar) !== false ||
                stripos($r->apellido, $buscar) !== false ||
                stripos($r->rut, $buscar) !== false ||
                str_contains((string) $r->id_reserva, $buscar) ||
                str_contains((string) $r->habitacion, $buscar)
            );
        }

        $reservas = $reservas->values();

        return view('estadias.index', compact('reservas'));
    }

    public function checkin(Request $request, int $id)
    {
        $hoy = now('America/Santiago')->toDateString();
        $fechaMinima = now('America/Santiago')->subDays(7)->toDateString();

        $datos = $request->validate([
            'fecha_checkin' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . $fechaMinima, 'before_or_equal:' . $hoy],
        ], [
            'fecha_checkin.after_or_equal' => 'El check-in no puede registrarse con más de 7 días de antigüedad.',
            'fecha_checkin.before_or_equal' => 'No puedes registrar un check-in con fecha futura.',
            'fecha_checkin.date_format' => 'Ingresa una fecha de check-in válida.',
        ]);

        $reserva = $this->mongo->byId('reservas', $id);

        if (!$reserva || $reserva->estado !== 'Confirmada' || $reserva->estadia !== null) {
            return redirect()->route('estadias.index')->withErrors('La reserva no admite check-in.');
        }

        foreach ($reserva->habitaciones as $item) {
            $habitacion = $this->mongo->byId('habitaciones', $item->habitacion_id);
            if (!$habitacion || !in_array($habitacion->estado, ['Disponible', 'Reservada'], true)) {
                return back()->withErrors('La habitación no está preparada para el ingreso.');
            }
        }

        $fechaCheckin = Carbon::parse($datos['fecha_checkin'], 'America/Santiago')
            ->setTimeFromTimeString(now('America/Santiago')->format('H:i:s'))
            ->utc()
            ->toDateTimeString();

        $estadia = [
            'id_estadia' => $id,
            'fecha_checkin' => $this->mongo->date($fechaCheckin),
            'fecha_checkout' => null,
            'estado' => 'Activa',
            'cargos' => [],
        ];

        $resultado = $this->mongo->collection('reservas')->updateOne(
            ['_id' => $id, 'estado' => 'Confirmada', 'estadia' => null],
            ['$set' => ['estadia' => $estadia]]
        );

        if ($resultado->getModifiedCount() !== 1) {
            return back()->withErrors('La estadía fue modificada por otra operación.');
        }

        foreach ($reserva->habitaciones as $item) {
            $this->mongo->collection('habitaciones')->updateOne(
                ['_id' => $item->habitacion_id],
                ['$set' => ['estado' => 'Ocupada']]
            );
        }

        return redirect()->route('estadias.index')->with('success', 'Check-in registrado en MongoDB.');
    }

    public function checkout(Request $request, int $id)
    {
        $reserva = $this->mongo->byId('reservas', $id);

        if (!$reserva || $reserva->estadia?->estado !== 'Activa') {
            return redirect()->route('estadias.index')->withErrors('No existe una estadía activa.');
        }

        $hoy = now('America/Santiago')->toDateString();
        $fechaCheckin = Carbon::parse($reserva->estadia->fecha_checkin, 'UTC')
            ->setTimezone('America/Santiago')
            ->toDateString();

        $datos = $request->validate([
            'fecha_checkout' => ['required', 'date_format:Y-m-d', 'after:' . $fechaCheckin],
        ], [
            'fecha_checkout.after' => 'El check-out debe ser al menos un día después del check-in.',
            'fecha_checkout.date_format' => 'Ingresa una fecha de check-out válida.',
        ]);

        if ($datos['fecha_checkout'] > $hoy) {
            $habitaciones = array_map(
                fn ($habitacion) => (int) $habitacion->habitacion_id,
                $reserva->habitaciones
            );

            $conflicto = $this->mongo->count('reservas', [
                '_id' => ['$ne' => $id],
                'habitaciones.habitacion_id' => ['$in' => $habitaciones],
                'estado' => ['$in' => ['Pendiente', 'Confirmada']],
                'fecha_entrada' => ['$lt' => $this->mongo->date($datos['fecha_checkout'])],
                'fecha_salida' => ['$gt' => $this->mongo->date($reserva->fecha_entrada)],
            ]) > 0;

            if ($conflicto) {
                return back()->withErrors([
                    'fecha_checkout' => 'No es posible programar esta salida porque existe otra reserva para la habitación durante ese período.',
                ]);
            }

            $resultado = $this->mongo->collection('reservas')->updateOne(
                ['_id' => $id, 'estadia.estado' => 'Activa'],
                ['$set' => ['fecha_salida' => $this->mongo->date($datos['fecha_checkout'])]]
            );

            if ($resultado->getMatchedCount() !== 1) {
                return back()->withErrors('La estadía ya no está activa.');
            }

            return redirect()->route('estadias.index')
                ->with('success', 'Fecha de salida programada. La estadía permanece activa hasta realizar el check-out.');
        }

        $fechaCheckout = Carbon::parse($datos['fecha_checkout'], 'America/Santiago')
            ->setTimeFromTimeString(now('America/Santiago')->format('H:i:s'))
            ->utc()
            ->toDateTimeString();

        $resultado = $this->mongo->collection('reservas')->updateOne(
            ['_id' => $id, 'estadia.estado' => 'Activa'],
            ['$set' => [
                'estadia.estado' => 'Finalizada',
                'estadia.fecha_checkout' => $this->mongo->date($fechaCheckout),
                'estado' => 'Finalizada',
            ]]
        );

        if ($resultado->getModifiedCount() !== 1) return back()->withErrors('La estadía ya fue finalizada.');

        foreach ($reserva->habitaciones as $item) {
            $this->mongo->collection('habitaciones')->updateOne(
                ['_id' => $item->habitacion_id],
                ['$set' => ['estado' => 'En limpieza']]
            );
        }

        return redirect()->route('estadias.index')->with('success', 'Check-out registrado en MongoDB.');
    }
}
