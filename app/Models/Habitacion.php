<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Habitacion extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'habitaciones';
    protected $primaryKey = '_id';
    public $timestamps = false;
    protected $fillable = ['numero', 'tipo', 'capacidad', 'precio', 'estado'];
}
