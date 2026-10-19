<?php

namespace App\Http\Controllers;

use App\Services\HotelMongo;
use Illuminate\Http\Request;

class PagoServicioController extends Controller
{
    public function __construct(private HotelMongo $mongo)
    {
    }

    public function index(Request $request)
    {
        $reservas = $this->mongo->reservationRows()->unique('id_reserva')->sortByDesc('id_reserva')->values();
        $servicios = $this->mongo->all('servicios', ['estado' => 'Activo'], ['sort' => ['nombre' => 1]])
            ->map(fn ($s) => (object) [...(array) $s, 'id_servicio' => $s->_id]);
        $account = $request->filled('reserva') ? $this->mongo->account((int) $request->reserva) : null;

        $reservaSeleccionada = $account['reservaSeleccionada'] ?? null;
        $estadia = $account['estadia'] ?? null;
        $cargos = $account['cargos'] ?? collect();
        $pagos = $account['pagos'] ?? collect();
        $totalHabitacion = $account['totalHabitacion'] ?? 0;
        $totalServicios = $account['totalServicios'] ?? 0;
        $totalCuenta = $account['totalCuenta'] ?? 0;
        $totalPagado = $account['totalPagado'] ?? 0;
        $saldo = $account['saldo'] ?? 0;

        return view('pagos.index', compact(
            'reservas', 'reservaSeleccionada', 'estadia', 'cargos', 'pagos', 'servicios',
            'totalHabitacion', 'totalServicios', 'totalCuenta', 'totalPagado', 'saldo'
        ));
    }

    public function agregarServicio(Request $request)
    {
        $datos = $request->validate([
            'id_reserva' => 'required|integer',
            'id_servicio' => 'required|integer',
            'cantidad' => 'required|integer|min:1',
        ]);

        $idReserva = (int) $datos['id_reserva'];
        $reserva = $this->mongo->byId('reservas', $idReserva);
        $servicio = $this->mongo->byId('servicios', (int) $datos['id_servicio']);

        if (!$reserva || $reserva->estadia?->estado !== 'Activa') {
            return redirect()->route('pagos.index', ['reserva' => $idReserva])->withErrors('Debes tener una estadía activa para registrar servicios.');
        }

        if (!$servicio || $servicio->estado !== 'Activo') {
            return back()->withErrors('El servicio no está disponible.');
        }

        $cantidad = (int) $datos['cantidad'];
        $nextCargo = collect($reserva->estadia->cargos)->max('id_cargo') + 1;
        $this->mongo->collection('reservas')->updateOne(
            ['_id' => $idReserva, 'estadia.estado' => 'Activa'],
            ['$push' => ['estadia.cargos' => [
                'id_cargo' => (int) $nextCargo,
                'servicio_id' => $servicio->_id,
                'servicio' => $servicio->nombre,
                'cantidad' => $cantidad,
                'precio_unitario' => $this->mongo->money($servicio->precio),
                'subtotal' => $this->mongo->money($cantidad * $servicio->precio),
                'fecha' => $this->mongo->date(now()->toDateTimeString()),
            ]]]
        );

        return redirect()->route('pagos.index', ['reserva' => $idReserva])->with('success', 'Servicio agregado a la estadía.');
    }

    public function registrarPago(Request $request)
    {
        $datos = $request->validate([
            'id_reserva' => 'required|integer',
            'monto' => 'required|numeric|min:1',
            'metodo_pago' => 'required|in:Efectivo,Tarjeta,Transferencia',
        ]);

        $idReserva = (int) $datos['id_reserva'];
        $account = $this->mongo->account($idReserva);

        if (!$account) return back()->withErrors('La reserva no existe.');
        if ($account['saldo'] <= 0) return back()->withErrors('La reserva no tiene saldo pendiente.');
        if ((float) $datos['monto'] > $account['saldo']) return back()->withErrors('El monto supera el saldo pendiente.');

        $this->mongo->collection('pagos')->insertOne([
            '_id' => $this->mongo->nextId('pagos'),
            'reserva_id' => $idReserva,
            'fecha_pago' => $this->mongo->date(now()->toDateTimeString()),
            'monto' => $this->mongo->money($datos['monto']),
            'metodo_pago' => $datos['metodo_pago'],
            'estado' => 'Pagado',
        ]);

        return redirect()->route('pagos.index', ['reserva' => $idReserva])->with('success', 'Pago registrado en MongoDB.');
    }
}
