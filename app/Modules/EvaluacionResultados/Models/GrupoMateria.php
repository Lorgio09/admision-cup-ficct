<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoMateria extends Model
{
    use HasFactory;

    // Forzamos el nombre de la tabla para que coincida con tu diagrama
    protected $table = 'grupo_materias';

    protected $fillable = ['grupo_id', 'materia_id', 'docente_id', 'horario_id'];

    public function grupo() { return $this->belongsTo(Grupo::class); }
    public function materia() { return $this->belongsTo(Materia::class); }
    public function docente() { return $this->belongsTo(Docente::class); }
    public function horario() { return $this->belongsTo(Horario::class); }
}