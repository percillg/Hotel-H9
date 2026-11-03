<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use MongoDB\Client;

class CrearCuentasHotel extends Command
{
    protected $signature = 'hotel:crear-cuentas {--atlas : Pedir URI de MongoDB Atlas de forma privada}';

    protected $description = 'Crear o actualizar las cuentas de recepcion y mantenimiento';

    public function handle(): int
    {
        $dsn = $this->option('atlas')
            ? (string) $this->secret('URI privada de MongoDB Atlas')
            : 'mongodb://127.0.0.1:27017';
        $base = (string) config('database.connections.mongodb.database');
        $servidor = parse_url($dsn, PHP_URL_HOST) ?: 'servidor desconocido';

        if ($dsn === '') {
            $this->error('No hay una conexion MongoDB configurada.');
            return self::FAILURE;
        }

        $this->warn('Destino: ' . $servidor . ' / ' . $base);
        if ($this->option('atlas') && !$this->confirm('¿Ya desplegaste los permisos de roles en Render?', false)) {
            $this->warn('Primero publica y verifica los permisos en Render.');
            return self::SUCCESS;
        }
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

        try {
            $cliente = new Client($dsn);
            $db = $cliente->selectDatabase($base);
            $usuarios = $db->selectCollection('usuarios');
            $encontrada = false;

            foreach ($db->listCollections(['filter' => ['name' => 'usuarios']]) as $info) {
                $encontrada = true;
                $opciones = $info->getOptions();
                $validador = $this->arreglo($opciones['validator'] ?? []);
                $enum = $validador['$jsonSchema']['properties']['rol']['enum'] ?? null;

                if (is_array($enum) && !in_array('Mantenimiento', $enum, true)) {
                    $validador['$jsonSchema']['properties']['rol']['enum'][] = 'Mantenimiento';
                    $db->modifyCollection('usuarios', ['validator' => $validador]);
                    $this->info('Validador de roles actualizado sin eliminar usuarios.');
                }
            }

            if (!$encontrada) {
                $this->error('No existe la colección usuarios. Primero prepara la base de datos.');
                return self::FAILURE;
            }

            $cuentas = [
                ['recep', 'Recepcion', 'HotelH9', 'Recepcionista', $contrasenaRecepcion],
                ['mantencion', 'Mantencion', 'HotelH9', 'Mantenimiento', $contrasenaMantencion],
            ];

            foreach ($cuentas as [$nombreUsuario, $nombre, $apellido, $rol, $contrasena]) {
                $anterior = $usuarios->findOne(['nombre_usuario' => $nombreUsuario]);
                $campos = [
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'nombre_usuario' => $nombreUsuario,
                    'rol' => $rol,
                    'password_hash' => Hash::make($contrasena),
                ];

                if ($anterior) {
                    $usuarios->updateOne(['nombre_usuario' => $nombreUsuario], ['$set' => $campos]);
                } else {
                    $ultimo = $usuarios->findOne([], ['sort' => ['_id' => -1], 'projection' => ['_id' => 1]]);
                    $campos['_id'] = $ultimo === null ? 1 : ((int) $ultimo['_id'] + 1);
                    $usuarios->insertOne($campos);
                }

                $this->info('Cuenta preparada: ' . $nombreUsuario . ' (' . $rol . ')');
            }

            $this->info('Las contraseñas se guardaron mediante hash. No se escribieron en archivos.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('No se pudieron configurar las cuentas. Verifica la conexion, el validador y los permisos de MongoDB. Tipo de error: ' . get_class($e));
            return self::FAILURE;
        }
    }

    private function arreglo(mixed $valor): mixed
    {
        if ($valor instanceof \Traversable) {
            $valor = iterator_to_array($valor);
        } elseif (is_object($valor)) {
            $valor = get_object_vars($valor);
        }

        if (is_array($valor)) {
            foreach ($valor as $clave => $elemento) {
                $valor[$clave] = $this->arreglo($elemento);
            }
        }

        return $valor;
    }
}