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
        // 1. Creamos o actualizamos tu usuario Administrador
        User::updateOrCreate(
            ['email' => 'lorgio.choque09@gmail.com'], // Condición de búsqueda
            [
                'name' => 'Lorgio',
                'password' => Hash::make('12345'), // Contraseña
                'rol' => 'admin'
            ]
        );
        // 2. Llamamos al seeder de las carreras
        $this->call([
            CarreraSeeder::class,
        ]); 
    }
}