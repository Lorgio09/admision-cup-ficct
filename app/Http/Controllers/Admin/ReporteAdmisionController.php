<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Postulante;
use App\Models\Gestion;
use Illuminate\Http\Request;

class ReporteAdmisionController extends Controller
{
    public function index()
    {
        // 1. Identificamos cuál es el semestre actual
        $gestionActiva = Gestion::where('is_active', true)->first();

        // Si el sistema está en pausa, mandamos la colección vacía
        if (!$gestionActiva) {
            return view('admin.admisiones.resultados', [
                'postulantes' => collect(), 
                'gestionActiva' => null
            ]);
        }

        // 2. Traemos a la "Élite": Solo postulantes de esta gestión que ya tienen promedio
        // Usamos "with" (Eager Loading) para no saturar la base de datos buscando carreras una por una
        $postulantes = Postulante::with(['carreraAdmitida', 'grupo'])
            ->whereHas('grupo', function($query) use ($gestionActiva) {
                $query->where('gestion_id', $gestionActiva->id);
            })
            ->whereNotNull('promedio') // Filtro clave: Solo entran los que ya tienen nota
            ->orderByDesc('promedio')  // Regla de Oro: Meritocracia (de 100 a 0)
            ->get();

        return view('admin.admisiones.resultados', compact('postulantes', 'gestionActiva'));
    }
}