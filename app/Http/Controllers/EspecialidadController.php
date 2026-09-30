<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    //
    public function index() //Pagina inicial
    {
        $especialidades = Especialidad::all(); //Hace un select all a la tabla
        //dd($especialidades);
        return view('especialidades.index', compact('especialidades')); //Llama a la vista y le pasa los datos
    }

    public function show(Especialidad $especialidad)
    {
        return view('especialidades.show', compact('especialidad'));
    }

    public function create()
    {
        return view('especialidades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'es_modalidad_imagen' => 'nullable|boolean', // Validamos el toggle
        ]);

        $especialidad = new Especialidad();
        $especialidad->nombre = $request->input('nombre');

        // Asignamos el valor del switch (interpreta 1/0, true/false correctamente)
        $especialidad->es_modalidad_imagen = $request->boolean('es_modalidad_imagen');

        $especialidad->save(); //Guarda en la BD

        return redirect()->route('especialidades.index')
            ->with('success', 'Especialidad creada correctamente.');
    }

    public function edit(Especialidad $especialidad)
    {
        return view('especialidades.edit', compact('especialidad'));
    }

    public function update(Request $request, Especialidad $especialidad)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'es_modalidad_imagen' => 'nullable|boolean',
        ]);

        if ($request->input('nombre') != null) {
            $especialidad->nombre = $request->input('nombre');
        }

        // Actualizamos el estado del toggle (indispensable para que se guarde si lo desmarcan)
        $especialidad->es_modalidad_imagen = $request->boolean('es_modalidad_imagen');

        $especialidad->save();

        return redirect()->route('especialidades.index')
            ->with('success', 'Especialidad actualizada correctamente.');
    }

    public function destroy(Especialidad $especialidad)
    {
        $tienePrimarios = $especialidad->procedimientos()->exists();
        $tieneSecundarios = $especialidad->procedimientos_secundarios()->exists();

        if ($tienePrimarios || $tieneSecundarios) {
            return redirect()->route('especialidades.index')
                ->with('error', 'No se puede eliminar: esta especialidad está asociada a procedimientos.');
        }

        $especialidad->delete();

        return redirect()->route('especialidades.index')
            ->with('success', 'Especialidad eliminada correctamente.');
    }
}