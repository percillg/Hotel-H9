<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class UsuarioHotel extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'usuarios';
    protected $primaryKey = '_id';
    public $timestamps = false;
    protected $fillable = ['nombre', 'apellido', 'nombre_usuario', 'password_hash', 'rol'];
}
