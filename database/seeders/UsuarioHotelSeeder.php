<?php

namespace Database\Seeders;

use App\Models\UsuarioHotel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Crea los usuarios base con los mismos id que usan las reservas en MongoDB
 * (usuario_id 1 = admin, 2 = recepcion). Las contraseñas quedan aleatorias:
 * después se fijan con "php artisan hotel:configurar-admin" y "hotel:crear-cuentas".
 */
class UsuarioHotelSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            [1, 'Administrador', 'Hotel', 'admin', 'Administrador'],
            [2, 'Recepcion', 'Hotel', 'recepcion', 'Recepcionista'],
        ];

        foreach ($usuarios as [$id, $nombre, $apellido, $usuario, $rol]) {
            UsuarioHotel::firstOrCreate(
                ['id_usuario' => $id],
                [
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'nombre_usuario' => $usuario,
                    'password_hash' => Hash::make(Str::random(32)),
                    'rol' => $rol,
                ]
            );
        }
    }
}
