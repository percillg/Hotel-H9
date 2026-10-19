<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Huesped extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'huespedes';
    protected $primaryKey = '_id';
    public $timestamps = false;
    protected $fillable = ['nombre', 'apellido', 'rut', 'telefono', 'email', 'direccion'];
}
