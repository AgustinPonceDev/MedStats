<?php

namespace App\Http\Controllers;

use App\Models\UsuarioPerfil;
use App\Models\User;
use Illuminate\Http\Request;

class UsuarioPerfilController extends Controller
{
    public function create()
    {
        return view('UsuarioPerfil.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'perfil' => 'required',
        ]);

        $perfil = new UsuarioPerfil();
        $perfil->perfil = $request->input('perfil');

        foreach (['admin', 'insumos', 'estadisticas', 'pacientes', 'camas', 'cirugias', 'estudios_medicos'] as $modulo) {
            $perfil->$modulo = $request->input($modulo) != null;
        }

        $perfil->save();

        return redirect()->route('UsuarioPerfil.index')->with('success', 'Perfil creado correctamente.');
    }

    public function edit(UsuarioPerfil $perfil)
    {
        return view('UsuarioPerfil.edit', compact('perfil'));
    }

    public function update(Request $request, UsuarioPerfil $perfil)
    {
        $request->validate([
            'perfil' => 'required',
        ]);

        $perfil->perfil = $request->input('perfil');

        foreach (['admin', 'insumos', 'estadisticas', 'pacientes', 'camas', 'cirugias', 'estudios_medicos'] as $modulo) {
            $perfil->$modulo = $request->input($modulo) != null;
        }

        $perfil->save();

        return redirect()->route('UsuarioPerfil.index')->with('success', 'Perfil actualizado correctamente.');
    }

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
