<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
    'nombre',
    'apellido',
    'email',
    'prefijo',
    'telefono',
    'user_id',
    ];

    public function sesiones()
    {
        return $this->hasMany(Sesion::class);
    }
}
