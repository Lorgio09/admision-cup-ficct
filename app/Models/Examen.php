<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    protected $fillable = ['postulante_id', 'materia_id', 'nota', 'observacion'];

    // Relación: Este examen le pertenece a un Postulante
    public function postulante()
    {
        return $this->belongsTo(Postulante::class);
    }

    // Relación: Este examen le pertenece a una Materia
    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
}