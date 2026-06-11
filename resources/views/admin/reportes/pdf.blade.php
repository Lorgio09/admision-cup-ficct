<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Admisiones</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: -20px 0; }
        .header { text-align: center; border-bottom: 2px solid #1a56db; padding-bottom: 10px; margin-bottom: 20px; }
        .univ-title { font-size: 18px; font-weight: bold; margin: 0; text-transform: uppercase; }
        .sys-title { font-size: 14px; color: #555; margin: 5px 0; }
        .report-title { font-size: 16px; font-weight: bold; margin: 15px 0; background-color: #f3f4f6; padding: 5px; text-align: center; border: 1px solid #ddd;}
        .meta-info { width: 100%; margin-bottom: 20px; font-size: 11px; }
        .meta-info td { padding: 3px; }
        
        /* Tabla Principal */
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #999; padding: 6px; text-align: left; }
        .table th { background-color: #1a56db; color: white; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        .table tr:nth-child(even) { background-color: #f9fafb; }
        .text-center { text-align: center; }
        .estado-aprobado { color: #047857; font-weight: bold; }
        .estado-reprobado { color: #dc2626; font-weight: bold; }
        
        /* Firmas */
        .signatures { width: 100%; margin-top: 50px; text-align: center; }
        .sig-line { width: 200px; border-top: 1px solid #000; margin: 0 auto; margin-top: 60px; padding-top: 5px; font-weight: bold; }
        
        /* Paginación Automática de DOMPDF */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="univ-title">Universidad Autónoma Gabriel René Moreno</h1>
        <p class="sys-title">Sistema Integrado de Admisiones CUP</p>
    </div>

    <table class="meta-info">
        <tr>
            <td><strong>Gestión Académica:</strong> {{ $gestionSeleccionada->nombre }}</td>
            <td class="text-center"><strong>Fecha de Emisión:</strong> {{ date('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Total Postulantes:</strong> {{ $kpis['total'] }}</td>
            <td class="text-center">
                <strong>Aprobados:</strong> <span class="estado-aprobado">{{ $kpis['aprobados'] }}</span> | 
                <strong>Reprobados:</strong> <span class="estado-reprobado">{{ $kpis['reprobados'] }}</span>
            </td>
        </tr>
    </table>

    <div class="report-title">
        ACTA OFICIAL DE RESULTADOS (ORDEN DE MÉRITO)
    </div>

    <table class="table">
        <thead>
            <tr>
                <th class="text-center" width="5%">Nº</th>
                <th width="15%">Cédula</th>
                <th width="45%">Nombre del Postulante</th>
                <th width="15%">Grupo</th>
                <th class="text-center" width="10%">Nota</th>
                <th class="text-center" width="10%">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($postulantes as $index => $postulante)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $postulante->ci }}</td>
                    <td>{{ mb_strtoupper($postulante->nombre) }}</td>
                    <td>{{ $postulante->grupo->nombre ?? 'N/A' }}</td>
                    <td class="text-center"><strong>{{ $postulante->promedio ?? '--' }}</strong></td>
                    <td class="text-center">
                        @if($postulante->promedio >= 60)
                            <span class="estado-aprobado">APR</span>
                        @elseif($postulante->promedio !== null && $postulante->promedio < 60)
                            <span class="estado-reprobado">REP</span>
                        @else
                            S/N
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay postulantes registrados en esta gestión.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                <div class="sig-line">Director de Carrera / Decano</div>
            </td>
            <td>
                <div class="sig-line">Jefatura de Registros y Admisiones</div>
            </td>
        </tr>
    </table>

</body>
</html>