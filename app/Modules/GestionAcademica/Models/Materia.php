<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $fillable = ['codigo', 'nombre'];

    // Relación: Una materia está en muchos exámenes
    public function examenes()
    {
        return $this->hasMany(Examen::class);
    }

    // Relación: Una materia puede tener muchos docentes asignados
    public function docentes()
    {
        return $this->hasMany(Docente::class);
    }
}