@extends('layouts.app')

@section('title', 'Editar Especialidad')

@section('contenido')
<div class="max-w-xl mx-auto px-4 py-4">

    <div class="flex justify-between items-center mb-6">
        <h1
            class="text-2xl font-bold bg-gradient-to-r from-[#1B7D8F] via-[#2BA8A0] to-[#245360] text-transparent bg-clip-text drop-shadow-md flex items-center gap-2 px-2">
            Editar Especialidad
        </h1>
    </div>

    <div class="card border">
        <div class="card-body">
            <form action="{{ route('especialidades.update', $especialidad) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nombre" class="form-label">Especialidad</label>
                    <input type="text" name="nombre" id="nombre" class="form-control"
                        value="{{ old('nombre', $especialidad->nombre) }}" >
                    @error('nombre')
                        <small class="text-danger"> {{ $message }} </small>
                    @enderror
                </div>

                {{-- Toggle Switch para Diagnóstico por Imágenes --}}
                <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg mb-3">
                    <div>
                        <span class="block text-sm font-semibold text-gray-700">¿Es Diagnóstico por Imágenes?</span>
                        <span class="block text-xs text-gray-500">Activalo si corresponde a una modalidad como Rayos, Tomografía, etc.</span>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        {{-- Input oculto pero funcional para Laravel (envía 1 si está marcado, 0 si no) --}}
                        <input type="hidden" name="es_modalidad_imagen" value="0">
                        <input type="checkbox" name="es_modalidad_imagen" value="1" id="es_modalidad_imagen"
                            class="sr-only peer"
                            {{ old('es_modalidad_imagen', $especialidad->es_modalidad_imagen) ? 'checked' : '' }}>

                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1B7D8F]"></div>
                    </label>
                </div>
                @error('es_modalidad_imagen')
                    <small class="text-danger block">{{ $message }}</small>
                @enderror

                <div class="flex justify-between pt-4">
                    <a href="{{ route('especialidades.index') }}"
                        class="btn btn-outline-danger px-5 py-2 rounded shadow-sm">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="bg-neutral-700 hover:bg-neutral-800 text-white font-semibold px-6 py-2 rounded-full shadow-md transition">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection