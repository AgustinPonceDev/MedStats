@extends('layouts.app')

@section('title', 'Editar Estudio')

@section('contenido')
    <div class="max-w-xl mx-auto px-4 py-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold bg-gradient-to-r from-[#1B7D8F] via-[#2BA8A0] to-[#245360] text-transparent bg-clip-text drop-shadow-md flex items-center gap-2 px-2">
                Editar Estudio
            </h1>
        </div>

        <div class="card border">
            <div class="card-body">
                <form action="{{ route('estudios.update', $estudio) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="especialidad_id" class="form-label fw-semibold">Modalidad</label>
                        <select name="especialidad_id" id="especialidad_id" class="form-control border shadow-sm">
                            @foreach ($especialidades as $especialidad)
                                <option value="{{ $especialidad->id }}" {{ old('especialidad_id', $estudio->especialidad_id) == $especialidad->id ? 'selected' : '' }}>
                                    {{ $especialidad->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('especialidad_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div>
                        <label for="nombre" class="form-label fw-semibold">Nombre del estudio</label>
                        <input type="text" name="nombre" id="nombre" class="form-control border shadow-sm"
                            value="{{ old('nombre', $estudio->nombre) }}">
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="flex justify-between pt-4">
                        <a href="{{ route('estudios.index') }}" class="btn btn-outline-danger px-5 py-2 rounded shadow-sm">
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
