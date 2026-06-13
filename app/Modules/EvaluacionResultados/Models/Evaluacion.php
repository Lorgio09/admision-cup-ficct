<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones';

    // Actualizamos los campos permitidos
    protected $fillable = [
        'postulante_id', 
        'materia_id', 
        'nota1', 
        'nota2', 
        'nota3'
    ];

    public function postulante()
    {
        return $this->belongsTo(Postulante::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }
}