<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Creamos el usuario Administrador por defecto
        User::firstOrCreate(
            ['email' => 'lorgio.choque09@gmail.com'], // Condición de búsqueda
            [
                'name' => 'Lorgio',
                'password' => Hash::make('12345') // Contraseña segura
            ]
        );
    }
}