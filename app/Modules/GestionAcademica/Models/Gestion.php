<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    protected $table = 'gestiones';
    protected $fillable = ['nombre', 'is_active'];

    public function carreras()
    {
        return $this->belongsToMany(Carrera::class, 'carrera_gestion')
                    ->withPivot('cupo_maximo')
                    ->withTimestamps();
    }
}