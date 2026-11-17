<?php

namespace App\Console\Commands;

use App\Models\UsuarioHotel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ConfigurarAdminHotel extends Command
{
    protected $signature = 'hotel:configurar-admin';

    protected $description = 'Configura la contraseña del administrador del hotel (tabla usuarios en MySQL)';

    public function handle(): int
    {
        $username = $this->ask('Nombre del usuario administrador', 'admin');
        $password = $this->secret('Nueva contraseña (mínimo 10 caracteres)');

        if (!is_string($password) || strlen($password) < 10) {
            $this->error('La contraseña debe tener al menos 10 caracteres.');
            return self::FAILURE;
        }

        $actualizados = UsuarioHotel::where('nombre_usuario', $username)
            ->where('rol', 'Administrador')
            ->update(['password_hash' => Hash::make($password)]);

        if ($actualizados === 0) {
            $this->error('No se encontró un usuario administrador con ese nombre.');
            return self::FAILURE;
        }

        $this->info('Contraseña del administrador actualizada correctamente.');
        return self::SUCCESS;
    }
}
