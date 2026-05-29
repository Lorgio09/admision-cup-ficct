<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones';

    protected $fillable = [
        'postulante_id',
        'materia_id',
        'nota',
    ];

    // Una evaluación pertenece a un postulante
    public function postulante()
    {
        return $this->belongsTo(Postulante::class);
    }

    // Una evaluación pertenece a una materia
    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
}