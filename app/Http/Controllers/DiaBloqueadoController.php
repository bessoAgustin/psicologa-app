<?php

namespace App\Http\Controllers;
use App\Models\DiaBloqueado;
use Illuminate\Http\Request;

class DiaBloqueadoController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month');

        return DiaBloqueado::where('fecha', 'like', "$month%")->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|unique:dias_bloqueados,fecha',
        ]);

        DiaBloqueado::create([
            'fecha' => $request->fecha,
        ]);

        return response()->json(['ok' => true]);
    }

    public function destroy($fecha)
    {
        DiaBloqueado::where('fecha', $fecha)->delete();

        return response()->json(['ok' => true]);
    }
}
