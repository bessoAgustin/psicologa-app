<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use App\Services\GeneradorDisponibilidad;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Paciente::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nombre', 'like', "%{$request->search}%")
                ->orWhere('apellido', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%")
                ->orWhere('telefono', 'like', "%{$request->search}%");
            });
        }

        return inertia('Pacientes/Index', [
            'pacientes' => $query->orderBy('id', 'desc')->paginate(10)->withQueryString(),
            'filters' => $request->only('search'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Pacientes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'email' => 'nullable|email',
        'prefijo' => 'required|string|max:10',
        'telefono' => 'required|string|max:50',
    ]);

    Paciente::create($data);

    return redirect()->route('pacientes.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente)
    {
        return inertia('Pacientes/Show', [
            'paciente' => $paciente
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paciente $paciente)
    {
        return inertia('Pacientes/Edit', [
            'paciente' => $paciente
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paciente $paciente)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'nullable|email',
            'prefijo' => 'nullable|string|max:10',
            'telefono' => 'nullable|string|max:50',
        ]);

        $paciente->update($data);

        return redirect()
        ->route('pacientes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {
        $paciente ->delete();
        return redirect()->route('pacientes.index');
    }
}
