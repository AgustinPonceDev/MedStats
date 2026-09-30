@extends('layouts.app')

@section('title', 'Catálogo de Estudios')

@section('contenido')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold bg-gradient-to-r from-[#1B7D8F] via-[#2BA8A0] to-[#245360] text-transparent bg-clip-text drop-shadow-md flex items-center gap-2 px-2">
                Catálogo de Estudios (Rayos / Tomografía)
            </h1>
            <a href="{{ route('estudios.create') }}"
                class="inline-block bg-neutral-700 hover:bg-neutral-800 text-white font-medium py-2 px-6 rounded-full shadow-md cursor-pointer transition duration-300"
                style="text-decoration: none;">
                Agregar Estudio
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card border">
            <div class="card-body">
                <p class="mb-3 text-secondary fw-semibold">
                    Cada estudio pertenece a una sola modalidad (Rayos o Tomografía). Estos son los que van a
                    aparecer en el segundo select al cargar un Estudio Médico.
                </p>

                <div class="bg-white shadow rounded-lg border border-gray-200 overflow-auto">
                    <table class="table table-hover table-bordered shadow-sm text-center rounded">
                        <thead>
                            <tr>
                                <th>Modalidad</th>
                                <th>Estudio</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($estudios as $estudio)
                                <tr>
                                    <td>{{ $estudio->especialidad->nombre ?? '-' }}</td>
                                    <td>{{ $estudio->nombre }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('estudios.edit', $estudio) }}"
                                            class="btn btn-outline-warning btn-sm me-1">Editar</a>
                                        <form action="{{ route('estudios.destroy', $estudio) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('¿Estás seguro de que querés eliminar este estudio?')">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        No hay estudios cargados todavía.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
