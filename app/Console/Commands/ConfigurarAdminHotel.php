<?php

namespace App\Console\Commands;

use App\Services\HotelMongo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ConfigurarAdminHotel extends Command
{
    protected $signature = 'hotel:configurar-admin';

    protected $description = 'Configura la contraseña del administrador del hotel en MongoDB';

    public function handle(HotelMongo $mongo): int
    {
        $username = $this->ask('Nombre del usuario administrador', 'admin');
        $password = $this->secret('Nueva contraseña (mínimo 10 caracteres)');

        if (!is_string($password) || strlen($password) < 10) {
            $this->error('La contraseña debe tener al menos 10 caracteres.');
            return self::FAILURE;
        }

        $resultado = $mongo->collection('usuarios')->updateOne(
            ['nombre_usuario' => $username, 'rol' => 'Administrador'],
            ['$set' => ['password_hash' => Hash::make($password)]]
        );

        if ($resultado->getMatchedCount() === 0) {
            $this->error('No se encontró un usuario administrador con ese nombre.');
            return self::FAILURE;
        }

        $this->info('Contraseña del administrador actualizada correctamente.');
        return self::SUCCESS;
    }
}
