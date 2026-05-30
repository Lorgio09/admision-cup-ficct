<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    // Agregamos turno y aula_id
    protected $fillable = ['nombre', 'turno', 'aula_id'];

    public function postulantes()
    {
        return $this->hasMany(Postulante::class);
    }
}