<?php

namespace App\Console\Commands;

use App\Models\UsuarioHotel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CrearCuentasHotel extends Command
{
    protected $signature = 'hotel:crear-cuentas';

    protected $description = 'Crear o actualizar las cuentas de recepcion y mantenimiento (tabla usuarios en MySQL)';

    public function handle(): int
    {
        $conexion = config('database.connections.mysql');
        $this->warn('Destino: ' . $conexion['host'] . ' / ' . $conexion['database']);
        if (!$this->confirm('¿Continuar con esta base de datos?', false)) {
            return self::SUCCESS;
        }

        $contrasenaRecepcion = $this->secret('Contraseña para recep');
        $contrasenaMantencion = $this->secret('Contraseña para mantencion');

        if (!is_string($contrasenaRecepcion) || strlen($contrasenaRecepcion) < 6 ||
            !is_string($contrasenaMantencion) || strlen($contrasenaMantencion) < 6) {
            $this->error('Las dos contraseñas deben tener al menos 6 caracteres.');
            return self::FAILURE;
        }

        $cuentas = [
            ['recep', 'Recepcion', 'HotelH9', 'Recepcionista', $contrasenaRecepcion],
            ['mantencion', 'Mantencion', 'HotelH9', 'Mantenimiento', $contrasenaMantencion],
        ];

        try {
            foreach ($cuentas as [$nombreUsuario, $nombre, $apellido, $rol, $contrasena]) {
                UsuarioHotel::updateOrCreate(
                    ['nombre_usuario' => $nombreUsuario],
                    [
                        'nombre' => $nombre,
                        'apellido' => $apellido,
                        'rol' => $rol,
                        'password_hash' => Hash::make($contrasena),
                    ]
                );
                $this->info('Cuenta preparada: ' . $nombreUsuario . ' (' . $rol . ')');
            }
        } catch (\Throwable $e) {
            $this->error('No se pudieron configurar las cuentas. Verifica la conexión MySQL y que corriste "php artisan migrate". Tipo de error: ' . get_class($e));
            return self::FAILURE;
        }

        $this->info('Las contraseñas se guardaron mediante hash. No se escribieron en archivos.');
        return self::SUCCESS;
    }
}
