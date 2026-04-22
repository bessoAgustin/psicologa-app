<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Sesion;

class GeneradorDisponibilidad
{
    public function generar($dias = 7)
    {
        $slots = [];

        $inicio = Carbon::now()->startOfDay();
        $fin = Carbon::now()->addDays($dias);

        for ($fecha = $inicio; $fecha->lte($fin); $fecha->addDay()) {

            // Solo lunes a viernes
            if ($fecha->isWeekend()) {
                continue;
            }

            $horaInicio = $fecha->copy()->setTime(19, 0);
            $horaFin = $fecha->copy()->setTime(21, 0);

            while ($horaInicio->lt($horaFin)) {

                $slot = [
                    'fecha' => $fecha->toDateString(),
                    'hora' => $horaInicio->format('H:i'),
                ];

                // Verificar si ya está reservado
                $ocupado = Sesion::where('fecha', $slot['fecha'])
                    ->where('hora', $slot['hora'])
                    ->where('estado', 'confirmada')
                    ->exists();

                if (! $ocupado) {
                    $slots[] = $slot;
                }

                // Duración de la sesión: 60 minutos
                $horaInicio->addMinutes(60);
            }
        }

        return $slots;
    }
}