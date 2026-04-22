<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Sesion;
use Illuminate\Http\Request;

class SesionController extends Controller
{
    public function storeGlobal(Request $request)
{
    $request->validate([
        'fecha' => 'required|date',
        'hora' => 'required',
    ]);

    $paciente = \App\Models\Paciente::where('user_id', auth()->id())->firstOrFail();

    $ocupado = Sesion::where('fecha', $request->fecha)
        ->where('hora', $request->hora)
        ->where('estado', 'confirmada')
        ->exists();

    if ($ocupado) {
        return back()->withErrors(['hora' => 'Este horario ya está ocupado.']);
    }

    $sesion = Sesion::create([
        'paciente_id' => $paciente->id,
        'fecha' => $request->fecha,
        'hora' => $request->hora,
        'estado' => 'pendiente',
        'precio' => 30,
    ]);

    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    $checkout = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Sesión de psicología - ' . $sesion->fecha . ' ' . $sesion->hora,
                ],
                'unit_amount' => $sesion->precio * 100,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => url('/confirmacion?sesion_id=' . $sesion->id),
        'cancel_url' => url('/disponibilidad'),
    ]);

    return response()->json(['url' => $checkout->url]);
    }



    public function porMes(Paciente $paciente, Request $request)
    {
    $month = $request->query('month');

    return $paciente->sesiones()
        ->where('fecha', 'like', "$month%")
        ->orderBy('fecha')
        ->orderBy('hora')
        ->get();
    }

    public function porDia(Paciente $paciente, Request $request)
    {
        $fecha = $request->query('fecha');

        return $paciente->sesiones()
            ->where('fecha', $fecha)
            ->orderBy('hora')
            ->get();
    }

    public function destroy(Paciente $paciente, Sesion $sesion)
    {
        if ($sesion->paciente_id !== $paciente->id) {
            abort(403);
        }

        $sesion->delete();

        return response()->json(['ok' => true]);
    }

    public function sesionesMesGlobal(Request $request)
    {
    $month = $request->query('month');

    return Sesion::with('paciente')
        ->where('fecha', 'like', "$month%")
        ->orderBy('fecha')
        ->orderBy('hora')
        ->get();
    }

    public function sesionesDiaGlobal(Request $request)
    {
        $fecha = $request->query('fecha');

        return Sesion::with('paciente')
            ->where('fecha', $fecha)
            ->orderBy('hora')
            ->get();
    }

    public function misCitas(Request $request)
    {
        $paciente = \App\Models\Paciente::where('user_id', $request->user()->id)->first();

        if (!$paciente) {
            return inertia('Citas/Index', ['sesiones' => []]);
        }

        $sesiones = Sesion::where('paciente_id', $paciente->id)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        return inertia('Citas/Index', [
            'sesiones' => $sesiones
        ]);
    }

    public function detalleCita(Sesion $sesion)
    {
        return inertia('Citas/Show', [
            'sesion' => $sesion
        ]);
    }

    public function cancelarCita(Sesion $sesion)
    {
        $sesion->update(['estado' => 'cancelada']);
        
        return inertia('Citas/Cancelada', [
            'sesion' => $sesion
        ]);
    }

    public function todasLasCitas()
    {
    $sesiones = Sesion::with('paciente')
        ->orderBy('fecha', 'desc')
        ->orderBy('hora', 'desc')
        ->get();

    return inertia('Admin/Citas', [
        'sesiones' => $sesiones
    ]);
    }

}