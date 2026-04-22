<?php

namespace App\Http\Controllers;

use App\Services\GeneradorDisponibilidad;
use Inertia\Inertia;

class DisponibilidadController extends Controller
{
    public function index(GeneradorDisponibilidad $generador)
    {
        $slots = $generador->generar(7);

        return inertia('Disponibilidad/Index', [
            'slots' => $slots
        ]);
    }
}