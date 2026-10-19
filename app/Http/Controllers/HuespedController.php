<?php

namespace App\Http\Controllers;

use App\Services\HotelMongo;
use Illuminate\Http\Request;

class HuespedController extends Controller
{
    public function __construct(private HotelMongo $mongo)
    {
    }

    public function index(Request $request)
    {
        $huespedes = $this->mongo->all('huespedes', [], ['sort' => ['apellido' => 1, 'nombre' => 1]]);

        if ($request->filled('buscar')) {
            $q = $request->buscar;
            $huespedes = $huespedes->filter(fn ($h) =>
                stripos($h->nombre, $q) !== false ||
                stripos($h->apellido, $q) !== false ||
                stripos($h->rut, $q) !== false ||
                stripos($h->email ?? '', $q) !== false
            )->values();
        }

        return view('huespedes.index', compact('huespedes'));
    }

    public function create()
    {
        return view('huespedes.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'rut' => 'required|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:150',
        ]);

        if ($this->mongo->one('huespedes', ['rut' => $datos['rut']])) {
            return back()->withInput()->withErrors(['rut' => 'Ya existe un huésped con ese RUT.']);
        }

        $this->mongo->collection('huespedes')->insertOne([
            '_id' => $this->mongo->nextId('huespedes'),
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'rut' => $datos['rut'],
            'telefono' => $datos['telefono'] ?? null,
            'email' => $datos['email'] ?? null,
            'direccion' => $datos['direccion'] ?? null,
        ]);

        return redirect()->route('huespedes.index')->with('success', 'Huésped registrado en MongoDB.');
    }

    public function show(int $id)
    {
        $huesped = $this->mongo->byId('huespedes', $id);
        abort_if(!$huesped, 404, 'Huésped no encontrado.');

        $reservas = $this->mongo->all('reservas', ['huesped._id' => $id], ['sort' => ['fecha_entrada' => -1]]);

        return view('huespedes.show', compact('huesped', 'reservas'));
    }

    public function edit(int $id)
    {
        $huesped = $this->mongo->byId('huespedes', $id);
        abort_if(!$huesped, 404, 'Huésped no encontrado.');

        return view('huespedes.edit', compact('huesped'));
    }

    public function update(Request $request, int $id)
    {
        $huesped = $this->mongo->byId('huespedes', $id);
        abort_if(!$huesped, 404, 'Huésped no encontrado.');

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:50'],
            'apellido' => ['required', 'string', 'max:50'],
            'rut' => ['required', 'string', 'max:20'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'direccion' => ['nullable', 'string', 'max:150'],
        ]);

        $datos['rut'] = trim($datos['rut']);

        if ($this->mongo->one('huespedes', ['rut' => $datos['rut'], '_id' => ['$ne' => $id]])) {
            return back()->withInput()->withErrors(['rut' => 'Ya existe otro huésped con ese RUT.']);
        }

        $cambios = [
            'nombre' => trim($datos['nombre']),
            'apellido' => trim($datos['apellido']),
            'rut' => $datos['rut'],
            'telefono' => $datos['telefono'] ?? null,
            'email' => $datos['email'] ?? null,
            'direccion' => $datos['direccion'] ?? null,
        ];

        $this->mongo->collection('huespedes')->updateOne(
            ['_id' => $id],
            ['$set' => $cambios]
        );

        $this->mongo->collection('reservas')->updateMany(
            ['huesped._id' => $id],
            ['$set' => [
                'huesped.nombre' => trim($cambios['nombre'] . ' ' . $cambios['apellido']),
                'huesped.rut' => $cambios['rut'],
            ]]
        );

        return redirect()->route('huespedes.show', $id)
            ->with('success', 'Datos del huésped actualizados en MongoDB.');
    }
}
