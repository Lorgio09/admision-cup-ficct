<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    // Campos que permitimos llenar masivamente
    protected $fillable = ['codigo', 'nombre', 'cupo'];

    // Relación: Una carrera tiene muchos postulantes (1ra opción)
    public function postulantesPrimeraOpcion()
    {
        return $this->hasMany(Postulante::class, 'carrera_primera_opcion_id');
    }

    // Relación: Una carrera tiene muchos postulantes (2da opción)
    public function postulantesSegundaOpcion()
    {
        return $this->hasMany(Postulante::class, 'carrera_segunda_opcion_id');
    }

    public function gestiones()
    {
        return $this->belongsToMany(Gestion::class, 'carrera_gestion')
                    ->withPivot('cupo_maximo')
                    ->withTimestamps();
    }
}
