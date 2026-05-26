<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = ['postulante_id', 'monto', 'fechaDePago'];

    // Relación: Este pago le pertenece a un Postulante
    public function postulante()
    {
        return $this->belongsTo(Postulante::class);
    }
}