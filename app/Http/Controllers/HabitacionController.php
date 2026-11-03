<?php

namespace App\Http\Controllers;

use App\Services\HotelMongo;
use Illuminate\Http\Request;

class HabitacionController extends Controller
{
    public function __construct(private HotelMongo $mongo)
    {
    }

    public function index(Request $request)
    {
        $habitaciones = $this->mongo->all('habitaciones', [], ['sort' => ['numero' => 1]])
            ->filter(function ($h) use ($request) {
                if ($request->filled('estado') && $h->estado !== $request->estado) return false;
                if ($request->filled('buscar') && stripos((string) $h->numero, $request->buscar) === false && stripos($h->tipo, $request->buscar) === false) return false;
                return true;
            })->map(fn ($h) => (object) [...(array) $h, 'id_habitacion' => $h->_id])->values();

        return view('habitaciones.index', compact('habitaciones'));
    }

    public function create()
    {
        return view('habitaciones.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'numero' => 'required|integer',
            'tipo' => 'required|string|max:50',
            'capacidad' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
            'estado' => 'required|in:Disponible,Reservada,Ocupada,En limpieza,Fuera de servicio',
        ]);

        if ($this->mongo->one('habitaciones', ['numero' => (int) $datos['numero']])) {
            return back()->withInput()->withErrors(['numero' => 'Ya existe una habitación con ese número.']);
        }

        $this->mongo->collection('habitaciones')->insertOne([
            '_id' => $this->mongo->nextId('habitaciones'),
            'numero' => (int) $datos['numero'],
            'tipo' => $datos['tipo'],
            'capacidad' => (int) $datos['capacidad'],
            'precio' => $this->mongo->money($datos['precio']),
            'estado' => $datos['estado'],
        ]);

        return redirect()->route('habitaciones.index')->with('success', 'Habitación registrada en MongoDB.');
    }

    public function cambiarEstado(Request $request, int $id)
    {
        $rol = session('usuario.rol');

        if (!in_array($rol, ['Administrador', 'Recepcionista', 'Mantenimiento'], true)) {
            abort(403, 'No tienes permiso para cambiar el estado de una habitación.');
        }

        $estados = $rol === 'Mantenimiento'
            ? 'Disponible,En limpieza,Fuera de servicio'
            : 'Disponible,Reservada,Ocupada,En limpieza,Fuera de servicio';

        $datos = $request->validate(['estado' => 'required|in:' . $estados]);
        $filtro = ['_id' => $id];

        if ($rol === 'Mantenimiento') {
            $filtro['estado'] = ['$nin' => ['Reservada', 'Ocupada']];
        }

        $resultado = $this->mongo->collection('habitaciones')->updateOne(
            $filtro,
            ['$set' => ['estado' => $datos['estado']]]
        );

        if ($resultado->getMatchedCount() === 0) {
            return back()->withErrors('No se pudo cambiar el estado: habitación inexistente o gestionada por recepción.');
        }

        return redirect()->route('habitaciones.index')->with('success', 'Estado actualizado correctamente.');
    }
}
