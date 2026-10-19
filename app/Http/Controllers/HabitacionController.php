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
        $datos = $request->validate(['estado' => 'required|in:Disponible,Reservada,Ocupada,En limpieza,Fuera de servicio']);
        $result = $this->mongo->collection('habitaciones')->updateOne(['_id' => $id], ['$set' => ['estado' => $datos['estado']]]);

        if ($result->getMatchedCount() === 0) return back()->withErrors('La habitación no existe.');

        return redirect()->route('habitaciones.index')->with('success', 'Estado actualizado en MongoDB.');
    }
}
