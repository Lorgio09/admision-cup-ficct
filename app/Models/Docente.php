<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $fillable = ['ci', 'nombre', 'telefono', 'user_id'];

    // Un docente tiene una cuenta de usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un docente puede enseñar en muchos grupos-materias
    public function grupoMaterias()
    {
        return $this->hasMany(GrupoMateria::class);
    }
}