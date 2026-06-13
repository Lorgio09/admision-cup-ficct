<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;

    protected $table = 'asignaciones'; // Forzamos el nombre en español

    protected $fillable = ['grupo_id', 'materia_id', 'docente_id', 'gestion_id'];

    public function grupo() { return $this->belongsTo(Grupo::class); }
    public function materia() { return $this->belongsTo(Materia::class); }
    public function docente() { return $this->belongsTo(Docente::class); }
    public function gestion() { return $this->belongsTo(Gestion::class); }
}