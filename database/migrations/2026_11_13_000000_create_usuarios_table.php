<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabla relacional de usuarios del hotel (login y roles).
 * El resto de los datos del hotel sigue en MongoDB.
 */
return new class extends Migration
{
    protected $connection = 'mysql';

    public function up(): void
    {
        Schema::connection($this->connection)->create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 50);
            $table->string('apellido', 50);
            $table->string('nombre_usuario', 50)->unique();
            $table->string('password_hash');
            $table->enum('rol', ['Administrador', 'Recepcionista', 'Personal de acceso', 'Mantenimiento']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('usuarios');
    }
};
