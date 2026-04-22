<?php

namespace App\Http\Controllers;

use App\Models\Sesion;
use App\Models\Paciente;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            $totalPacientes = Paciente::count();
            $citasHoy = Sesion::where('fecha', today())->where('estado', 'confirmada')->count();
            $citasPendientes = Sesion::where('estado', 'pendiente')->count();
            $proximasCitas = Sesion::with('paciente')
                ->where('fecha', '>=', today())
                ->where('estado', 'confirmada')
                ->orderBy('fecha')->orderBy('hora')
                ->take(5)->get();

            return inertia('Dashboard', [
                'rol' => 'admin',
                'totalPacientes' => $totalPacientes,
                'citasHoy' => $citasHoy,
                'citasPendientes' => $citasPendientes,
                'proximasCitas' => $proximasCitas,
            ]);
        }

        // Paciente
        $paciente = Paciente::where('user_id', $user->id)->first();
        $proximaCita = null;
        $totalCitas = 0;

        if ($paciente) {
            $proximaCita = Sesion::where('paciente_id', $paciente->id)
                ->where('fecha', '>=', today())
                ->where('estado', 'confirmada')
                ->orderBy('fecha')->orderBy('hora')
                ->first();

            $totalCitas = Sesion::where('paciente_id', $paciente->id)
                ->where('estado', 'confirmada')
                ->count();
        }

        return inertia('Dashboard', [
            'rol' => 'paciente',
            'proximaCita' => $proximaCita,
            'totalCitas' => $totalCitas,
        ]);
    }
}