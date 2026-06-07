<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materia;

class MateriaSeeder extends Seeder
{
    public function run(): void
    {
        $materias = [
            ['codigo' => 'COMP', 'nombre' => 'Computación'],
            ['codigo' => 'FIS', 'nombre' => 'Física'],
            ['codigo' => 'ING', 'nombre' => 'Inglés'],
            ['codigo' => 'MAT', 'nombre' => 'Matemática'],
        ];

        foreach ($materias as $materia) {
            // Buscamos por código para evitar duplicados si corres el seeder dos veces
            Materia::firstOrCreate(
                ['codigo' => $materia['codigo']], 
                ['nombre' => $materia['nombre']]
            );
        }
    }
}