<?php

namespace App\Http\Controllers;

use App\Models\Estudio;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EstudioController extends Controller
{
    public function index()
    {
        $estudios = Estudio::with('especialidad')->orderBy('especialidad_id')->orderBy('nombre')->get();

        return view('estudios.index', compact('estudios'));
    }

    public function create()
    {
        $especialidades = Especialidad::modalidadesImagen()->orderBy('nombre')->get();

        return view('estudios.create', compact('especialidades'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'especialidad_id' => [
                'required', 'integer',
                Rule::exists('especialidads', 'id')->where(fn ($q) => $q->where('es_modalidad_imagen', true)),
            ],
            'nombre' => [
                'required', 'string', 'max:150',
                Rule::unique('estudios')->where(fn ($q) => $q->where('especialidad_id', $request->input('especialidad_id'))),
            ],
        ]);

        Estudio::create($data);

        return redirect()->route('estudios.index')->with('success', 'Estudio creado correctamente.');
    }

    public function edit(Estudio $estudio)
    {
        $especialidades = Especialidad::modalidadesImagen()->orderBy('nombre')->get();

        return view('estudios.edit', compact('estudio', 'especialidades'));
    }

    public function update(Request $request, Estudio $estudio)
    {
        $data = $request->validate([
            'especialidad_id' => [
                'required', 'integer',
                Rule::exists('especialidads', 'id')->where(fn ($q) => $q->where('es_modalidad_imagen', true)),
            ],
            'nombre' => [
                'required', 'string', 'max:150',
                Rule::unique('estudios')->where(fn ($q) => $q->where('especialidad_id', $request->input('especialidad_id')))->ignore($estudio->id),
            ],
        ]);

        $estudio->update($data);

        return redirect()->route('estudios.index')->with('success', 'Estudio actualizado correctamente.');
    }

    public function destroy(Estudio $estudio)
    {
        if ($estudio->estudios_medicos()->exists()) {
            return redirect()->route('estudios.index')
                ->with('error', 'No se puede eliminar: este estudio ya fue utilizado en un diagnóstico.');
        }

        $estudio->delete();

        return redirect()->route('estudios.index')->with('success', 'Estudio eliminado correctamente.');
    }
}
