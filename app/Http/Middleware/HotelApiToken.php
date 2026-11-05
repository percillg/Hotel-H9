<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HotelApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $tokenConfigurado = (string) config('hotelh9_api.token', '');

        if ($tokenConfigurado === '') {
            return response()->json([
                'error' => [
                    'codigo' => 'API_NO_CONFIGURADA',
                    'mensaje' => 'Servicio no disponible.',
                ],
            ], 503);
        }

        $tokenRecibido = (string) ($request->bearerToken() ?? '');

        if ($tokenRecibido === '' || !hash_equals($tokenConfigurado, $tokenRecibido)) {
            return response()->json([
                'error' => [
                    'codigo' => 'NO_AUTORIZADO',
                    'mensaje' => 'Token de API ausente o inválido.',
                ],
            ], 401);
        }

        return $next($request);
    }
}
