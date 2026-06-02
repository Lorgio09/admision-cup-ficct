<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    // turno y aula_id
    protected $fillable = ['nombre', 'turno', 'aula_id', 'gestion_id', 'dias', 'hora_inicio', 'hora_fin'];

    public function postulantes()
    {
        return $this->hasMany(Postulante::class);
    }
}