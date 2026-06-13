<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gestion;
use App\Models\Postulante;
use App\Models\Grupo;
use App\Models\Asignacion;
use App\Models\Evaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PostulantesExport;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        // 1. Traemos TODAS las gestiones registradas (de la más nueva a la más antigua) para el combo desplegable
        $gestiones = Gestion::orderByDesc('id')->get();

        // 2. Determinamos qué gestión mostrar
        // Si el usuario seleccionó una del combo, usamos esa. Si no, buscamos la activa por defecto.
        if ($request->has('gestion_id') && $request->gestion_id != '') {
            $gestionSeleccionada = Gestion::find($request->gestion_id);
        } else {
            $gestionSeleccionada = Gestion::where('is_active', true)->first();
        }

        // Si el sistema está totalmente vacío, mandamos todo en cero
        if (!$gestionSeleccionada) {
            return view('admin.reportes.index', [
                'gestiones' => $gestiones,
                'gestionActiva' => null,
                'kpis' => ['total' => 0, 'aprobados' => 0, 'reprobados' => 0, 'promedio' => 0, 'grupos' => 0],
                'rankingGrupos' => collect(),
                'estadisticasMaterias' => collect(),
                'docentesPorGrupo' => collect(),
                'listaGeneral' => collect()
            ]);
        }

        // ===================================================================
        // TODO ESTE BLOQUE QUEDA IGUAL, SOLO CAMBIAMOS $gestionActiva POR $gestionSeleccionada
        // ===================================================================
        
        $postulantes = Postulante::whereHas('grupo', function($q) use ($gestionSeleccionada) {
            $q->where('gestion_id', $gestionSeleccionada->id);
        })->get();

        $kpis = [
            'total' => $postulantes->count(),
            'aprobados' => $postulantes->where('promedio', '>=', 60)->count(),
            'reprobados' => $postulantes->whereNotNull('promedio')->where('promedio', '<', 60)->count(),
            'promedio' => round($postulantes->avg('promedio') ?? 0, 2),
            'grupos' => Grupo::where('gestion_id', $gestionSeleccionada->id)->count()
        ];

        $rankingGrupos = Postulante::select('grupo_id', DB::raw('count(*) as total_aprobados'))
            ->whereHas('grupo', function($q) use ($gestionSeleccionada) {
                $q->where('gestion_id', $gestionSeleccionada->id);
            })
            ->where('promedio', '>=', 60)
            ->groupBy('grupo_id')
            ->orderByDesc('total_aprobados')
            ->with('grupo')
            ->take(5)
            ->get();

        $estadisticasMaterias = Evaluacion::with('materia')
            ->whereHas('postulante.grupo', function($q) use ($gestionSeleccionada) {
                $q->where('gestion_id', $gestionSeleccionada->id);
            })
            ->get()
            ->groupBy('materia_id')
            ->map(function ($evaluaciones) {
                $aprobados = 0; $reprobados = 0;
                $materiaNombre = $evaluaciones->first()->materia->nombre ?? 'Desconocida';
                
                foreach ($evaluaciones as $ev) {
                    if ($ev->nota1 !== null && $ev->nota2 !== null && $ev->nota3 !== null) {
                        $prom = ($ev->nota1 + $ev->nota2 + $ev->nota3) / 3;
                        if ($prom >= 60) { $aprobados++; } else { $reprobados++; }
                    }
                }
                return (object)['nombre' => $materiaNombre, 'aprobados' => $aprobados, 'reprobados' => $reprobados];
            });

        $docentesPorGrupo = Asignacion::with(['docente', 'grupo', 'materia'])
            ->where('gestion_id', $gestionSeleccionada->id)
            ->orderBy('grupo_id')
            ->get()
            ->groupBy('grupo.nombre');

        // Retornamos la vista incluyendo la colección de todas las gestiones
        return view('admin.reportes.index', [
            'gestiones' => $gestiones,
            'gestionActiva' => $gestionSeleccionada, // Mantenemos el nombre para no romper tu vista
            'kpis' => $kpis,
            'rankingGrupos' => $rankingGrupos,
            'estadisticasMaterias' => $estadisticasMaterias,
            'docentesPorGrupo' => $docentesPorGrupo,
            'listaGeneral' => $postulantes
        ]);
    }

public function exportarPdf(Request $request)
    {
        if ($request->has('gestion_id') && $request->gestion_id != '') {
            $gestionSeleccionada = Gestion::find($request->gestion_id);
        } else {
            $gestionSeleccionada = Gestion::where('is_active', true)->first();
        }

        if (!$gestionSeleccionada) {
            return back()->with('error', 'No hay gestión para exportar.');
        }

        // Traemos la lista ordenada por promedio para el acta oficial
        $postulantes = Postulante::with('grupo')
            ->whereHas('grupo', function($q) use ($gestionSeleccionada) {
                $q->where('gestion_id', $gestionSeleccionada->id);
            })
            ->orderByDesc('promedio')
            ->get();

        $kpis = [
            'total' => $postulantes->count(),
            'aprobados' => $postulantes->where('promedio', '>=', 60)->count(),
            'reprobados' => $postulantes->whereNotNull('promedio')->where('promedio', '<', 60)->count(),
        ];

        // Generamos el PDF usando una vista especial (sin Tailwind)
        $pdf = Pdf::loadView('admin.reportes.pdf', compact('gestionSeleccionada', 'postulantes', 'kpis'));
        
        // Formato vertical (A4)
        $pdf->setPaper('A4', 'portrait');

        // Retornamos el archivo para su descarga
        return $pdf->download('Acta_Resultados_'.$gestionSeleccionada->nombre.'.pdf');
    }

    public function exportarExcel(Request $request)
    {
        // 1. Capturamos la gestión que el administrador está visualizando
        if ($request->has('gestion_id') && $request->gestion_id != '') {
            $gestionSeleccionada = Gestion::find($request->gestion_id);
        } else {
            $gestionSeleccionada = Gestion::where('is_active', true)->first();
        }

        if (!$gestionSeleccionada) {
            return back()->with('error', 'No se encontró la gestión para exportar.');
        }

        // 2. Ejecutamos la descarga del archivo .xlsx de forma nativa
        $nombreArchivo = 'Reporte_Postulantes_' . $gestionSeleccionada->nombre . '.xlsx';

        return Excel::download(new PostulantesExport($gestionSeleccionada->id), $nombreArchivo);
    }
}