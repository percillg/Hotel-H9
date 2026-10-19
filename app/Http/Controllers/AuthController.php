<?php

namespace App\Http\Controllers;

use App\Services\HotelMongo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private HotelMongo $mongo)
    {
    }

    public function showLogin()
    {
        return session()->has('usuario') ? redirect()->route('dashboard') : view('auth.login');
    }

    public function login(Request $request)
    {
        $datos = $request->validate([
            'nombre_usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = $this->mongo->one('usuarios', ['nombre_usuario' => $datos['nombre_usuario']]);

        if (!$usuario || !Hash::check($datos['password'], $usuario->password_hash)) {
            return back()->withInput($request->only('nombre_usuario'))->withErrors(['login' => 'Usuario o contraseña incorrectos.']);
        }

        $request->session()->regenerate();
        session(['usuario' => [
            'id_usuario' => $usuario->_id,
            'nombre' => $usuario->nombre,
            'apellido' => $usuario->apellido,
            'nombre_usuario' => $usuario->nombre_usuario,
            'rol' => $usuario->rol,
        ]]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('usuario');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
