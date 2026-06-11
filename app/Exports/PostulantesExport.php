<?php

namespace App\Exports;

use App\Models\Postulante;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PostulantesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $gestionId;
    private $rowNumber = 0;

    // Recibimos el ID de la gestión seleccionada por el Administrador
    public function __construct($gestionId)
    {
        $this->gestionId = $gestionId;
    }

    /**
     * Consulta a la Base de Datos filtrada por gestión
     */
    public function collection()
    {
        return Postulante::with('grupo')
            ->whereHas('grupo', function($q) {
                $q->where('gestion_id', $this->gestionId);
            })
            ->orderByDesc('promedio') // Orden por mérito
            ->get();
    }

    /**
     * Encabezados de la Tabla en Excel
     */
    public function headings(): array
    {
        return [
            'NRO.',
            'CÉDULA DE IDENTIDAD',
            'NOMBRE COMPLETO DEL POSTULANTE',
            'GRUPO ASIGNADO',
            'PROMEDIO FINAL',
            'ESTADO ACADÉMICO'
        ];
    }

    /**
     * Mapeo de datos (Controla cómo se imprime cada columna en la fila)
     */
    public function map($postulante): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $postulante->ci,
            mb_strtoupper($postulante->nombre), // Normalizado a mayúsculas
            $postulante->grupo->nombre ?? 'SIN GRUPO',
            $postulante->promedio ?? '0.00',
            $postulante->estado ?? 'EN PROCESO'
        ];
    }
}