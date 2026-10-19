<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Servicio extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'servicios';
    protected $primaryKey = '_id';
    public $timestamps = false;
    protected $fillable = ['nombre', 'descripcion', 'precio', 'estado'];
}
