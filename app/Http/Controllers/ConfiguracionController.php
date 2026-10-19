<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $nombreHotel = session('nombre_hotel', 'Gestión Hotel');

        return view('configuracion.index', compact('nombreHotel'));
    }

    public function actualizar(Request $request)
    {
        $request->validate([
            'nombre_hotel' => 'required|string|max:100'
        ]);

        session([
            'nombre_hotel' => $request->nombre_hotel
        ]);

        return redirect()
            ->route('configuracion.index')
            ->with('success', 'Configuración actualizada correctamente.');
    }
}