<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HotelAutenticado
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('usuario.id_usuario')) {
            return redirect()->route('login');
        }

        $rol = $request->session()->get('usuario.rol');
        $ruta = $request->route()?->getName();

        if ($rol === 'Administrador') {
            return $next($request);
        }

        $recepcion = [
            'dashboard',
            'habitaciones.index',
            'habitaciones.estado',
            'reservas.index',
            'reservas.create',
            'reservas.store',
            'huespedes.index',
            'huespedes.create',
            'huespedes.store',
            'huespedes.show',
            'huespedes.edit',
            'huespedes.update',
            'estadias.index',
            'estadias.checkin',
            'estadias.checkout',
            'pagos.index',
            'pagos.servicio',
            'pagos.registrar',
        ];

        $mantenimiento = [
            'habitaciones.index',
            'habitaciones.estado',
        ];

        if ($rol === 'Recepcionista' && in_array($ruta, $recepcion, true)) {
            return $next($request);
        }

        if ($rol === 'Mantenimiento' && in_array($ruta, $mantenimiento, true)) {
            return $next($request);
        }

        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}
