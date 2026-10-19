<?php

namespace App\Http\Controllers;

use App\Services\HotelMongo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioHotelController extends Controller
{
    public function __construct(private HotelMongo $mongo)
    {
    }

    public function index()
    {
        $usuarios = $this->mongo->all('usuarios', [], ['sort' => ['nombre' => 1]]);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        if (session('usuario.rol') !== 'Administrador') abort(403);
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        if (session('usuario.rol') !== 'Administrador') abort(403);

        $datos = $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'nombre_usuario' => 'required|string|max:50',
            'password' => 'required|string|min:10',
            'rol' => 'required|in:Administrador,Recepcionista,Personal de acceso',
        ]);

        if ($this->mongo->one('usuarios', ['nombre_usuario' => $datos['nombre_usuario']])) {
            return back()->withInput()->withErrors(['nombre_usuario' => 'El nombre de usuario ya existe.']);
        }

        $this->mongo->collection('usuarios')->insertOne([
            '_id' => $this->mongo->nextId('usuarios'),
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'nombre_usuario' => $datos['nombre_usuario'],
            'password_hash' => Hash::make($datos['password']),
            'rol' => $datos['rol'],
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }
}
