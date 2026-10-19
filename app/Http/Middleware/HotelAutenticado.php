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

        return $next($request);
    }
}
