<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    protected $fillable = [
        'paciente_id',
        'fecha',
        'hora',
        'estado',
        'precio',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

}
