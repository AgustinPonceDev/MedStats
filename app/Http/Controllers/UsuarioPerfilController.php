<?php

namespace App\Http\Controllers;

use App\Models\UsuarioPerfil;
use App\Models\User;
use App\Models\Servicio;
use Illuminate\Http\Request;

class UsuarioPerfilController extends Controller
{
    /**
     * Muestra el formulario para crear un nuevo perfil
     */
    public function create()
    {
        $servicios = Servicio::all();
        return view('UsuarioPerfil.create', compact('servicios'));
    }

    /**
     * Almacena un nuevo perfil en la base de datos
     */
    public function store(Request $request)
    {
        $request->validate([
            'perfil' => 'required',
            'servicio_id' => 'nullable|exists:servicios,id',
        ]);

        $perfil = new UsuarioPerfil();
        $perfil->perfil = $request->input('perfil');
        $perfil->servicio_id = $request->input('servicio_id');

        foreach (['admin', 'insumos', 'estadisticas', 'pacientes', 'camas', 'cirugias', 'estudios_medicos'] as $modulo) {
            $perfil->$modulo = $request->input($modulo) != null;
        }

        $perfil->save();

        return redirect()->route('UsuarioPerfil.index')->with('success', 'Perfil creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un perfil
     */
    public function edit(UsuarioPerfil $perfil)
    {
        $servicios = Servicio::all();
        return view('UsuarioPerfil.edit', compact('perfil', 'servicios'));
    }

    /**
     * Actualiza un perfil en la base de datos
     */
    public function update(Request $request, UsuarioPerfil $perfil)
    {
        $request->validate([
            'perfil' => 'required',
            'servicio_id' => 'nullable|exists:servicios,id',
        ]);

        $perfil->perfil = $request->input('perfil');
        $perfil->servicio_id = $request->input('servicio_id');

        foreach (['admin', 'insumos', 'estadisticas', 'pacientes', 'camas', 'cirugias', 'estudios_medicos'] as $modulo) {
            $perfil->$modulo = $request->input($modulo) != null;
        }

        $perfil->save();

        return redirect()->route('UsuarioPerfil.index')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Elimina un perfil de la base de datos
     */
    public function destroy(UsuarioPerfil $perfil)
    {
        $perfil->delete();
        return redirect()->route('UsuarioPerfil.index')->with('success', 'Perfil eliminado correctamente.');
    }

    public function index()
    {
        $perfiles = UsuarioPerfil::all();
        $usuarios = User::all();

        return view('UsuarioPerfil.index', compact('perfiles', 'usuarios'));
    }

    public function actualizarRol(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|integer',
        ]);

        $usuario = User::findOrFail($id);
        $usuario->role = $request->role;
        $usuario->save();

        return redirect()->route('UsuarioPerfil.index')->with('success', 'Rol actualizado correctamente.');
    }
}
