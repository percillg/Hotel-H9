<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Usuarios del sistema (login y roles).
 * Viven en la base relacional (MySQL). El resto de los modelos sigue en MongoDB.
 * En MongoDB las reservas guardan "usuario_id" = id_usuario de esta tabla.
 */
class UsuarioHotel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    protected $fillable = ['id_usuario', 'nombre', 'apellido', 'nombre_usuario', 'password_hash', 'rol'];
    protected $hidden = ['password_hash'];

    public static function porNombreUsuario(string $nombreUsuario): ?self
    {
        return static::where('nombre_usuario', $nombreUsuario)->first();
    }
}
