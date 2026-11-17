<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Carga inicial de la base relacional (solo usuarios).
     * Los datos del hotel se cargan en MongoDB con database/mongodb/01_importar.js
     */
    public function run(): void
    {
        $this->call(UsuarioHotelSeeder::class);
    }
}
