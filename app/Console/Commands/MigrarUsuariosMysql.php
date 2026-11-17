<?php

namespace App\Console\Commands;

use App\Models\UsuarioHotel;
use App\Services\HotelMongo;
use Illuminate\Console\Command;

/**
 * Copia (una sola vez) los usuarios de la colección "usuarios" de MongoDB
 * a la tabla "usuarios" de MySQL, conservando el mismo id y el mismo hash
 * de contraseña. Así las reservas (usuario_id) siguen apuntando bien y
 * nadie tiene que cambiar su clave. Al terminar elimina la colección
 * "usuarios" de MongoDB (pide confirmación).
 */
class MigrarUsuariosMysql extends Command
{
    protected $signature = 'hotel:migrar-usuarios';

    protected $description = 'Copia los usuarios de MongoDB a MySQL (mismo id y contraseña) y elimina la colección de MongoDB';

    public function handle(HotelMongo $mongo): int
    {
        try {
            $usuarios = $mongo->all('usuarios', [], ['sort' => ['_id' => 1]]);
        } catch (\Throwable $e) {
            $this->error('No se pudo leer MongoDB. Tipo de error: ' . get_class($e));
            return self::FAILURE;
        }

        if ($usuarios->isEmpty()) {
            $this->warn('La colección usuarios de MongoDB está vacía. Nada que copiar.');
            return self::SUCCESS;
        }

        foreach ($usuarios as $u) {
            UsuarioHotel::updateOrCreate(
                ['id_usuario' => (int) $u->_id],
                [
                    'nombre' => $u->nombre,
                    'apellido' => $u->apellido,
                    'nombre_usuario' => $u->nombre_usuario,
                    'password_hash' => $u->password_hash,
                    'rol' => $u->rol,
                ]
            );
            $this->line('Copiado: ' . $u->nombre_usuario . ' (id ' . $u->_id . ', ' . $u->rol . ')');
        }

        $this->info($usuarios->count() . ' usuario(s) copiados a MySQL.');

        $enMysql = UsuarioHotel::whereIn('id_usuario', $usuarios->pluck('_id')->map(fn ($id) => (int) $id))->count();
        if ($enMysql !== $usuarios->count()) {
            $this->error('No todos los usuarios quedaron en MySQL. No se borró nada de MongoDB.');
            return self::FAILURE;
        }

        if ($this->confirm('¿Eliminar la colección usuarios de MongoDB? (ya está copiada en MySQL)', true)) {
            $mongo->collection('usuarios')->drop();
            $this->info('Colección usuarios eliminada de MongoDB.');
        }

        return self::SUCCESS;
    }
}
