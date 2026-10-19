<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Pago extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'pagos';
    protected $primaryKey = '_id';
    public $timestamps = false;
    protected $fillable = ['reserva_id', 'fecha_pago', 'monto', 'metodo_pago', 'estado'];
}
