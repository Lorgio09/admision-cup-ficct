<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $fillable = ['nro_aula', 'tipo', 'facultad_id'];

    public function facultad()
    {
        return $this->belongsTo(Facultad::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }
}
