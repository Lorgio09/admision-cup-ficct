<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

// Agregamos todos los campos personales y las dos opciones de carrera
#[Fillable([
    'ci', 
    'nombre', 
    'correo', 
    'sexo', 
    'telefono', 
    'direccion', 
    'carrera_primera_opcion_id', 
    'carrera_segunda_opcion_id', 
    'recibo_pago', 
    'certificado_bachiller', 
    'estado', 
    'user_id'
])]
class Postulante extends Model
{
    use HasFactory;

    // Relación: Primera opción de carrera
    public function primeraOpcion()
    {
        return $this->belongsTo(Carrera::class, 'carrera_primera_opcion_id');
    }

    // Relación: Segunda opción de carrera
    public function segundaOpcion()
    {
        return $this->belongsTo(Carrera::class, 'carrera_segunda_opcion_id');
    }

    // Relación: Un postulante tiene una cuenta de usuario (cuando se apruebe)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}