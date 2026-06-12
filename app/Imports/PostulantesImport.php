<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Postulante;
use App\Models\Gestion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PostulantesImport implements ToModel, WithHeadingRow
{
    protected $gestionActiva;

    public function __construct()
    {
        // Buscamos el periodo donde se van a matricular
        $this->gestionActiva = Gestion::where('is_active', true)->first();
    }

    /**
     * Este método se ejecuta por CADA FILA del archivo Excel/CSV
     */
    public function model(array $row)
    {
        if (!isset($row['ci']) || !isset($row['correo'])) {
            return null;
        }

        // 1. Búsqueda de Carreras
        $idOpcion1 = null;
        $idOpcion2 = null;

        if (isset($row['primera_opcion'])) {
            $carrera1 = \App\Models\Carrera::where('nombre', 'LIKE', '%' . trim($row['primera_opcion']) . '%')->first();
            $idOpcion1 = $carrera1 ? $carrera1->id : null;
        }

        if (isset($row['segunda_opcion'])) {
            $carrera2 = \App\Models\Carrera::where('nombre', 'LIKE', '%' . trim($row['segunda_opcion']) . '%')->first();
            $idOpcion2 = $carrera2 ? $carrera2->id : null;
        }

        // 2. Búsqueda del Grupo (Modo Blindado para PostgreSQL)
        $idGrupo = null;
        
        // Verificamos que la celda exista y no esté vacía
        if (isset($row['grupo']) && !empty(trim($row['grupo'])) && $this->gestionActiva) {
            
            // Usamos ILIKE y comodines % por si el Excel trae espacios fantasmas
            $grupoBuscado = trim($row['grupo']);
            
            $grupo = \App\Models\Grupo::where('nombre', 'ILIKE', '%' . $grupoBuscado . '%')
                                      ->where('gestion_id', $this->gestionActiva->id)
                                      ->first();
                                      
            $idGrupo = $grupo ? $grupo->id : null;
            
            // TRUCO DE DEBUG: Descomenta la siguiente línea si quieres ver qué está leyendo el sistema
            //dd("Excel dice: " . $grupoBuscado, "ID encontrado: " . $idGrupo);
        }
        // 3. Extracciones blindadas
        $sexoExtraido = isset($row['sexo']) ? substr(strtoupper(trim($row['sexo'])), 0, 1) : 'O';
        $telefonoExtraido = isset($row['telefono']) ? trim($row['telefono']) : 'S/N';
        $direccionExtraida = isset($row['direccion']) ? trim($row['direccion']) : 'Sin especificar';

        // 4. Crear Usuario
        $user = User::firstOrCreate(
            ['email' => trim($row['correo'])],
            [
                'name'     => trim($row['nombre']),
                'rol'      => 'postulante',
                'password' => Hash::make(trim($row['ci'])), 
            ]
        );

        // 5. Crear Postulante asignando el grupo_id
        Postulante::updateOrCreate(
            ['ci' => trim($row['ci'])],
            [
                'user_id'   => $user->id,
                'nombre'    => trim($row['nombre']),
                'correo'    => trim($row['correo']),
                'sexo'      => $sexoExtraido,
                'telefono'  => $telefonoExtraido,
                'direccion' => $direccionExtraida,
                'carrera_primera_opcion_id' => $idOpcion1,
                'carrera_segunda_opcion_id' => $idOpcion2,
                'grupo_id'  => $idGrupo, // Asignación directa del grupo
                'estado'    => 'inscrito'
            ]
        );

        return null;
    }
}