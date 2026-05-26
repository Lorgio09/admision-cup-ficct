<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    use HasFactory;

    protected $fillable = [
        'ci', 'nombre', 'correo', 'sexo', 'telefono', 'direccion', 
        'carrera_primera_opcion_id', 'carrera_segunda_opcion_id'
    ];

    // Relación: Pertenece a una Carrera (como 1ra opción)
    public function primeraOpcion()
    {
        return $this->belongsTo(Carrera::class, 'carrera_primera_opcion_id');
    }

    // Relación: Pertenece a una Carrera (como 2da opción)
    public function segundaOpcion()
    {
        return $this->belongsTo(Carrera::class, 'carrera_segunda_opcion_id');
    }

    // Relación: Tiene un solo pago
    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    // Relación: Tiene muchos exámenes (notas)
    public function examenes()
    {
        return $this->hasMany(Examen::class);
    }
}