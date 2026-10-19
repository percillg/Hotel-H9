<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use MongoDB\BSON\Decimal128;
use MongoDB\BSON\Regex;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Collection as MongoCollection;

class HotelMongo
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(config('database.connections.mongodb.dsn'));
    }

    public function collection(string $name): MongoCollection
    {
        return $this->client->selectCollection(config('database.connections.mongodb.database'), $name);
    }

    public function all(string $collection, array $filter = [], array $options = []): Collection
    {
        $documents = $this->collection($collection)->find($filter, $options);

        return collect(iterator_to_array($documents, false))
            ->map(fn ($document) => $this->normalize($document));
    }

    public function one(string $collection, array $filter): ?object
    {
        $document = $this->collection($collection)->findOne($filter);

        return $document === null ? null : $this->normalize($document);
    }

    public function byId(string $collection, int $id): ?object
    {
        return $this->one($collection, ['_id' => $id]);
    }

    public function count(string $collection, array $filter = []): int
    {
        return $this->collection($collection)->countDocuments($filter);
    }

    public function nextId(string $collection): int
    {
        $last = $this->collection($collection)->findOne([], ['sort' => ['_id' => -1], 'projection' => ['_id' => 1]]);

        return $last === null ? 1 : (int) $last['_id'] + 1;
    }

    public function date(string $value): UTCDateTime
    {
        return new UTCDateTime(Carbon::parse($value, 'UTC')->getTimestamp() * 1000);
    }

    public function money(float|int|string $value): Decimal128
    {
        return new Decimal128(number_format((float) $value, 2, '.', ''));
    }

    public function search(string $text): Regex
    {
        return new Regex(preg_quote($text, '/'), 'i');
    }

    public function reservations(): Collection
    {
        return $this->all('reservas', [], ['sort' => ['fecha_entrada' => 1]]);
    }

    public function reservationRows(): Collection
    {
        return $this->reservations()->flatMap(function ($reserva) {
            $nombreCompleto = trim($reserva->huesped->nombre);
            $partes = preg_split('/\s+/', $nombreCompleto);
            $apellido = count($partes) > 1 ? array_pop($partes) : '';
            $nombre = implode(' ', $partes);
            $estadia = $reserva->estadia;

            return collect($reserva->habitaciones)->map(fn ($habitacion) => (object) [
                'id_reserva' => $reserva->_id,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'rut' => $reserva->huesped->rut,
                'habitacion' => $habitacion->numero,
                'id_habitacion' => $habitacion->habitacion_id,
                'tipo' => $habitacion->tipo,
                'fecha_reserva' => $reserva->fecha_reserva,
                'fecha_entrada' => $reserva->fecha_entrada,
                'fecha_salida' => $reserva->fecha_salida,
                'estado' => $reserva->estado,
                'estado_reserva' => $reserva->estado,
                'id_estadia' => $estadia?->id_estadia,
                'estado_estadia' => $estadia?->estado,
                'fecha_checkin' => $estadia?->fecha_checkin,
                'fecha_checkout' => $estadia?->fecha_checkout,
            ]);
        });
    }

    public function account(int $id): ?array
    {
        $reserva = $this->byId('reservas', $id);

        if (!$reserva) {
            return null;
        }

        $partes = preg_split('/\s+/', trim($reserva->huesped->nombre));
        $apellido = count($partes) > 1 ? array_pop($partes) : '';
        $nombre = implode(' ', $partes);
        $seleccionada = (object) [
            'id_reserva' => $reserva->_id,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'rut' => $reserva->huesped->rut,
            'fecha_entrada' => $reserva->fecha_entrada,
            'fecha_salida' => $reserva->fecha_salida,
            'estado' => $reserva->estado,
        ];

        $noches = (int) Carbon::parse($reserva->fecha_entrada, 'UTC')->startOfDay()->diffInDays(Carbon::parse($reserva->fecha_salida, 'UTC')->startOfDay());
        $totalHabitacion = collect($reserva->habitaciones)->sum(fn ($item) => $item->precio_noche * $noches);
        $cargos = collect($reserva->estadia?->cargos ?? [])->map(fn ($cargo) => (object) [
            'id_cargo' => $cargo->id_cargo,
            'servicio' => $cargo->servicio,
            'cantidad' => $cargo->cantidad,
            'precio' => $cargo->precio_unitario,
            'subtotal' => $cargo->subtotal,
            'fecha' => $cargo->fecha,
        ])->sortByDesc('fecha')->values();
        $pagos = $this->all('pagos', ['reserva_id' => $id], ['sort' => ['fecha_pago' => -1]])
            ->map(fn ($pago) => (object) [
                'id_pago' => $pago->_id,
                'fecha_pago' => $pago->fecha_pago,
                'monto' => $pago->monto,
                'metodo_pago' => $pago->metodo_pago,
                'estado' => $pago->estado,
            ]);
        $totalServicios = $cargos->sum('subtotal');
        $totalPagado = $pagos->where('estado', 'Pagado')->sum('monto');

        return [
            'reservaSeleccionada' => $seleccionada,
            'estadia' => $reserva->estadia,
            'cargos' => $cargos,
            'pagos' => $pagos,
            'totalHabitacion' => $totalHabitacion,
            'totalServicios' => $totalServicios,
            'totalCuenta' => $totalHabitacion + $totalServicios,
            'totalPagado' => $totalPagado,
            'saldo' => $totalHabitacion + $totalServicios - $totalPagado,
        ];
    }

    private function normalize(mixed $value): mixed
    {
        if ($value instanceof UTCDateTime) {
            return $value->toDateTime()->format('Y-m-d H:i:s');
        }

        if ($value instanceof Decimal128) {
            return (float) (string) $value;
        }

        if ($value instanceof \MongoDB\Model\BSONArray) {
            return array_map(fn ($item) => $this->normalize($item), $value->getArrayCopy());
        }

        if (is_array($value)) {
            return array_map(fn ($item) => $this->normalize($item), $value);
        }

        if ($value instanceof \Traversable || is_object($value)) {
            $result = new \stdClass();
            foreach ($value as $key => $item) {
                $result->{$key} = $this->normalize($item);
            }
            return $result;
        }

        return $value;
    }
}
