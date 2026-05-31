<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    public function run(): void
    {
        $carreras = [
            ['codigo' => '187-3', 'nombre' => 'Ingeniería en Sistemas'],
            ['codigo' => '187-4', 'nombre' => 'Ingeniería Informática'],
            ['codigo' => '187-5', 'nombre' => 'Ingeniería en Redes y Telecomunicaciones'],
            ['codigo' => '187-6', 'nombre' => 'Ingeniería en Robotica']
        ];

        foreach ($carreras as $carrera) {
            // Ahora buscamos por 'codigo' para evitar duplicados. 
            // Si no encuentra el código, crea la carrera con su nombre.
            Carrera::firstOrCreate(
                ['codigo' => $carrera['codigo']], 
                ['nombre' => $carrera['nombre']]
            );
        }
    }
}