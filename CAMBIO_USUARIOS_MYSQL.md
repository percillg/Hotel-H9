# Usuarios en MySQL + resto en MongoDB

## Qué cambió
| Dato | Base |
|---|---|
| usuarios (login, roles) | **MySQL** – tabla `usuarios` |
| habitaciones, huéspedes, reservas, pagos, servicios | **MongoDB** (sin cambios) |

En MongoDB las reservas siguen guardando `usuario_id`, que ahora corresponde a `id_usuario` de la tabla MySQL.

Archivos:
- `database/migrations/2026_11_13_000000_create_usuarios_table.php` (nuevo) – crea la tabla.
- `app/Models/UsuarioHotel.php` – ahora usa la conexión `mysql`.
- `app/Http/Controllers/AuthController.php` y `UsuarioHotelController.php` – leen/escriben usuarios en MySQL.
- `app/Console/Commands/ConfigurarAdminHotel.php` y `CrearCuentasHotel.php` – trabajan sobre MySQL.
- `app/Console/Commands/MigrarUsuariosMysql.php` (nuevo) – copia los usuarios de MongoDB a MySQL con el mismo id y contraseña, revisa que estén todos y después elimina la colección `usuarios` de MongoDB.
- `database/mongodb/01_importar.js` – ya no crea la colección `usuarios` (y la elimina si existe).
- `database/mongodb/02_verificar.js` – revisa 5 colecciones y que `usuarios` ya no exista.
- `database/mongodb/usuarios.json` – **eliminado**.
- `database/seeders/UsuarioHotelSeeder.php` (nuevo) y `DatabaseSeeder.php`.
- `.env.example` – variables de las dos bases.

## Cómo ponerlo en marcha
1. Crear la base vacía en MySQL: `CREATE DATABASE gestion_hotel;`
2. En `.env` completar `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` y dejar `DB_CONNECTION=mysql`. Las variables `MONGODB_*` quedan iguales.
3. `php artisan migrate`
4. Cargar los usuarios (elegir una):
   - Si ya tienen usuarios en MongoDB: `php artisan hotel:migrar-usuarios` (conserva contraseñas y al final borra la colección de MongoDB). **Hacerlo antes de volver a correr `01_importar.js`**, porque ese script ahora borra la colección sin copiarla.
   - Si parten de cero: `php artisan db:seed` y luego `php artisan hotel:configurar-admin` y `php artisan hotel:crear-cuentas`.
5. `php artisan serve` y probar el login.

## Render
Agregar en Environment: `DB_CONNECTION=mysql` y los datos `DB_*` de un MySQL externo (Render no ofrece MySQL gratis; sirve Aiven, Railway, etc.). Después del despliegue, correr `php artisan migrate --force` y `php artisan hotel:migrar-usuarios` desde la Shell del servicio. El Dockerfile ya trae `pdo_mysql`.

Si la colección se borró sin copiar (por ejemplo, corriendo primero `01_importar.js`), se recupera con `php artisan db:seed` + `hotel:configurar-admin` + `hotel:crear-cuentas`.
