<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\HotelMongo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class HotelApiController extends Controller
{
    public function __construct(private HotelMongo $mongo)
    {
    }

    public function estado(): JsonResponse
    {
        return response()->json([
            'servicio' => 'HotelH9',
            'estado' => 'activo',
        ]);
    }

    public function habitaciones(Request $request): JsonResponse
    {
        $datos = $this->validarListado($request, [
            'estado' => ['sometimes', 'string', Rule::in([
                'Disponible', 'Reservada', 'Ocupada', 'En limpieza', 'Fuera de servicio',
            ])],
        ]);

        if ($datos instanceof JsonResponse) {
            return $datos;
        }

        $limite = (int) ($datos['limite'] ?? 20);
        $pagina = (int) ($datos['pagina'] ?? 1);
        $filtro = isset($datos['estado']) ? ['estado' => $datos['estado']] : [];
        $total = $this->mongo->count('habitaciones', $filtro);
        $registros = $this->mongo->all('habitaciones', $filtro, [
            'sort' => ['numero' => 1],
            'skip' => ($pagina - 1) * $limite,
            'limit' => $limite,
            'projection' => [
                '_id' => 1, 'numero' => 1, 'tipo' => 1, 'capacidad' => 1,
                'precio' => 1, 'estado' => 1,
            ],
        ])->map(fn ($h) => [
            'id' => $h->_id,
            'numero' => $h->numero,
            'tipo' => $h->tipo,
            'capacidad' => $h->capacidad,
            'precio' => $h->precio,
            'estado' => $h->estado,
        ])->values()->all();

        return response()->json([
            'data' => $registros,
            'meta' => ['total' => $total, 'pagina' => $pagina, 'limite' => $limite],
        ]);
    }

    public function habitacion(int $id): JsonResponse
    {
        $h = $this->mongo->byId('habitaciones', $id);

        if (!$h) {
            return response()->json([
                'error' => [
                    'codigo' => 'NO_ENCONTRADO',
                    'mensaje' => 'La habitación solicitada no existe.',
                ],
            ], 404);
        }

        return response()->json([
            'data' => [
                'id' => $h->_id,
                'numero' => $h->numero,
                'tipo' => $h->tipo,
                'capacidad' => $h->capacidad,
                'precio' => $h->precio,
                'estado' => $h->estado,
            ],
        ]);
    }

    public function reservas(Request $request): JsonResponse
    {
        $datos = $this->validarListado($request, [
            'estado' => ['sometimes', 'string', Rule::in([
                'Pendiente', 'Confirmada', 'Finalizada', 'Cancelada',
            ])],
        ]);

        if ($datos instanceof JsonResponse) {
            return $datos;
        }

        $limite = (int) ($datos['limite'] ?? 20);
        $pagina = (int) ($datos['pagina'] ?? 1);
        $filtro = isset($datos['estado']) ? ['estado' => $datos['estado']] : [];
        $total = $this->mongo->count('reservas', $filtro);
        $registros = $this->mongo->all('reservas', $filtro, [
            'sort' => ['fecha_entrada' => -1],
            'skip' => ($pagina - 1) * $limite,
            'limit' => $limite,
            'projection' => [
                '_id' => 1, 'estado' => 1, 'fecha_entrada' => 1,
                'fecha_salida' => 1, 'habitaciones.numero' => 1,
                'habitaciones.tipo' => 1,
            ],
        ])->map(fn ($r) => [
            'id' => $r->_id,
            'estado' => $r->estado,
            'fecha_entrada' => substr((string) $r->fecha_entrada, 0, 10),
            'fecha_salida' => substr((string) $r->fecha_salida, 0, 10),
            'habitaciones' => collect($r->habitaciones ?? [])->map(fn ($h) => [
                'numero' => $h->numero ?? null,
                'tipo' => $h->tipo ?? null,
            ])->values()->all(),
        ])->values()->all();

        return response()->json([
            'data' => $registros,
            'meta' => ['total' => $total, 'pagina' => $pagina, 'limite' => $limite],
        ]);
    }

    public function estadisticas(): JsonResponse
    {
        $estados = ['Disponible', 'Reservada', 'Ocupada', 'En limpieza', 'Fuera de servicio'];
        $habitacionesPorEstado = [];

        foreach ($estados as $estado) {
            $habitacionesPorEstado[$estado] = $this->mongo->count('habitaciones', ['estado' => $estado]);
        }

        return response()->json([
            'data' => [
                'habitaciones' => $this->mongo->count('habitaciones'),
                'habitaciones_por_estado' => $habitacionesPorEstado,
                'reservas' => $this->mongo->count('reservas'),
            ],
        ]);
    }

    private function validarListado(Request $request, array $reglas): array|JsonResponse
    {
        $validador = Validator::make($request->query(), array_merge([
            'limite' => ['sometimes', 'integer', 'between:1,50'],
            'pagina' => ['sometimes', 'integer', 'between:1,10000'],
        ], $reglas));

        if ($validador->fails()) {
            return response()->json([
                'error' => [
                    'codigo' => 'DATOS_INVALIDOS',
                    'mensaje' => 'Los parámetros de la solicitud no son válidos.',
                    'campos' => $validador->errors()->toArray(),
                ],
            ], 422);
        }

        return $validador->validated();
    }
}
