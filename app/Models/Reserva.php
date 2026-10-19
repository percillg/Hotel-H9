<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Reserva extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'reservas';
    protected $primaryKey = '_id';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id', 'huesped', 'fecha_reserva', 'fecha_entrada',
        'fecha_salida', 'estado', 'habitaciones', 'estadia'
    ];
}
