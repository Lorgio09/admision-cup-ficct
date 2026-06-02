<?php

namespace Database\Seeders;

use App\Models\Carrera;
use Illuminate\Database\Seeder;

class CarreraSeeder extends Seeder
{
    public function run(): void
    {
        $carreras = [
            // Inicializamos el cupo en 0 hasta que el administrador lo asigne en el sistema
            ['codigo' => '187-3', 'nombre' => 'Ingeniería en Sistemas'],
            ['codigo' => '187-4', 'nombre' => 'Ingeniería Informática'],
            ['codigo' => '187-5', 'nombre' => 'Ingeniería en Redes y Telecomunicaciones'],
            ['codigo' => '187-6', 'nombre' => 'Ingeniería en Robotica',]
        ];

        foreach ($carreras as $carrera) {
            Carrera::firstOrCreate(
                ['codigo' => $carrera['codigo']], 
                $carrera
            );
        }
    }
}