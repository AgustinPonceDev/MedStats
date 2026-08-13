@extends('layouts.app')
@section('title', 'Detalle de Estudio Médico')
@section('contenido')
    <div class="max-w-4xl mx-auto px-6 py-8">

        {{-- Encabezado Institucional con Badge de Ámbito --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Detalle de Registro</span>
                <h1
                    class="text-2xl font-bold bg-gradient-to-r from-[#1B7D8F] via-[#2BA8A0] to-[#245360] text-transparent bg-clip-text drop-shadow-sm">
                    Estudio de Diagnóstico por Imágenes
                </h1>
            </div>
            <div>
                <span
                    class="inline-flex items-center gap-3 bg-white px-4 py-1.5 rounded-full border border-gray-200 shadow-sm">
                    <span
                        class="text-xs font-bold uppercase tracking-wider {{ $estudio->ia == 'I' ? 'text-gray-400' : 'text-blue-700' }}">Ambulatorio</span>
                    <span
                        class="relative w-10 h-5 rounded-full transition-colors {{ $estudio->ia == 'I' ? 'bg-amber-400' : 'bg-blue-200' }}">
                        <span
                            class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow transition-all {{ $estudio->ia == 'I' ? 'left-5' : 'left-0.5' }}"></span>
                    </span>
                    <span
                        class="text-xs font-bold uppercase tracking-wider {{ $estudio->ia == 'I' ? 'text-amber-700' : 'text-gray-400' }}">Internado</span>
                </span>
            </div>
        </div>

        {{-- Tarjeta Principal --}}
        <div class="bg-white shadow-sm rounded-2xl p-6 border border-gray-100 space-y-6 text-[15px]">

            {{-- Sección 1: Información del Paciente --}}
            {{-- Sección 1: Información del Paciente --}}
            <div>
                <h3 class="text-xs font-bold text-[#1B7D8F] uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4"></i> Datos del Paciente
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase">Apellido y Nombre</p>
                        <p class="font-bold text-gray-800 mt-0.5">
                            {{ optional($estudio->paciente)->apellido }}, {{ optional($estudio->paciente)->nombre }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase">Documento (DNI)</p>
                        <p class="font-mono font-bold text-gray-700 mt-0.5">
                            {{ optional($estudio->paciente)->dni ?? 'No registrado' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase">Fecha de Registro</p>
                        <p class="font-semibold text-gray-800 mt-0.5">
                            {{ $estudio->fecha ? $estudio->fecha->format('d/m/Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase">Hora del Estudio</p>
                        <p class="font-semibold text-gray-800 mt-0.5">
                            {{ $estudio->hora_estudio ? \Carbon\Carbon::parse($estudio->hora_estudio)->format('H:i') : '-' }}
                        </p>
                    </div>
                </div>
            </div>
            </div>

            {{-- Sección 2: Especificaciones del Estudio --}}
            <div>
                <h3 class="text-xs font-bold text-[#1B7D8F] uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i data-lucide="activity" class="w-4 h-4"></i> Especificación Técnica
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase">Modalidad</p>
                        <p class="text-gray-800 font-semibold mt-0.5">
                            {{ optional($estudio->especialidad)->nombre ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase">Estudio Real</p>
                        <p class="text-gray-800 font-semibold mt-0.5">
                            {{ optional($estudio->estudio)->nombre ?? $estudio->tipo_estudio }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase">Regiones Vistas</p>
                        <p class="text-gray-800 font-semibold mt-0.5">
                            {{ $estudio->regiones ?? '-' }}
                        </p>
                        @if (!$estudio->regiones && $estudio->regiones_legacy)
                            <p class="text-xs text-amber-600 mt-0.5" title="Dato cargado antes del cambio a numérico">
                                Registro anterior: "{{ $estudio->regiones_legacy }}"
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Sección 3: Consumo de Insumos y Medios de Contraste --}}
            <div>
                <h3 class="text-xs font-bold text-[#1B7D8F] uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4"></i> Control de Insumos y Contraste
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div class="bg-teal-50/50 p-3 rounded-lg border border-teal-100/50 text-center">
                        <span class="block text-xs font-medium text-teal-800 uppercase">Contraste 50ml</span>
                        <span class="block text-xl font-bold text-teal-900 mt-1">{{ $estudio->cont_50ml ?? 0 }} u.</span>
                    </div>
                    <div class="bg-teal-50/50 p-3 rounded-lg border border-teal-100/50 text-center">
                        <span class="block text-xs font-medium text-teal-800 uppercase">Contraste 100ml</span>
                        <span class="block text-xl font-bold text-teal-900 mt-1">{{ $estudio->cont_100ml ?? 0 }} u.</span>
                    </div>
                    <div class="bg-teal-50/50 p-3 rounded-lg border border-teal-100/50 text-center">
                        <span class="block text-xs font-medium text-teal-800 uppercase">Jeringa Prellenada</span>
                        <span class="block text-xl font-bold text-teal-900 mt-1">{{ $estudio->jeringa_prellenada ?? 0 }}
                            u.</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50/80 p-3.5 rounded-lg border border-gray-100">
                        <span class="block text-xs text-gray-400 font-semibold uppercase mb-1">Material Descartable</span>
                        <p class="text-gray-700 text-sm font-medium">{{ $estudio->descartables ?: 'Ninguno registrado' }}
                        </p>
                    </div>
                    <div class="bg-gray-50/80 p-3.5 rounded-lg border border-gray-100">
                        <span class="block text-xs text-gray-400 font-semibold uppercase mb-1">Otros y Agujas de
                            Punción</span>
                        <p class="text-gray-700 text-sm font-medium">{{ $estudio->otros_agujas ?: 'Ninguno registrado' }}
                        </p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Sección 3.5: Insumos de Stock consumidos --}}
            <div>
                <h3 class="text-xs font-bold text-[#1B7D8F] uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i data-lucide="package-minus" class="w-4 h-4"></i> Insumos de Stock Descontados
                </h3>
                @if ($estudio->movimientos_stock->isEmpty())
                    <p class="text-sm text-gray-500">No se registraron insumos de stock para este estudio.</p>
                @else
                    <div class="bg-gray-50/60 rounded-lg border border-gray-100 divide-y divide-gray-100">
                        @foreach ($estudio->movimientos_stock as $mov)
                            <div class="flex justify-between items-center px-4 py-2 text-sm">
                                <span class="text-gray-700 font-medium">
                                    {{ optional(optional($mov->get_stock)->get_medicamento)->nombre ?? 'Insumo eliminado' }}
                                </span>
                                <span class="text-red-600 font-bold">{{ $mov->cantidad }} u.</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Aviso interactivo: Redirige a la edición para corregir insumos --}}
<div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-sm text-blue-800 mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <div>
        Los insumos de stock consumidos por este estudio pueden ser modificados si hubo un error en la carga.
    </div>
    <a href="{{ route('estudios_medicos.edit', $estudio->id) }}" class="underline font-semibold hover:text-blue-900 shrink-0">
        Corregir en el editor →
    </a>
</div>

            <hr class="border-gray-100">

            {{-- Sección 4: Médico Solicitante y Observaciones --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <p class="text-xs text-gray-400 font-semibold uppercase">Médico Solicitante</p>
                    <p class="text-gray-800 font-semibold mt-1">
                        {{ optional($estudio->medico_solicitante)->apellido }},
                        {{ optional($estudio->medico_solicitante)->nombre }}
                    </p>
                    <span class="text-gray-400 text-xs mt-0.5 block font-mono">
                        {{ optional($estudio->medico_solicitante)->matricula ? 'M.P. ' . optional($estudio->medico_solicitante)->matricula : 'Sin matrícula cargada' }}
                    </span>
                </div>

                <div class="md:col-span-2">
                    <p class="text-xs text-gray-400 font-semibold uppercase mb-1">Observaciones / Informe Clínico</p>
                    <div
                        class="bg-gray-50/60 rounded-lg p-3 border border-gray-100 font-mono text-xs text-gray-600 whitespace-pre-wrap leading-relaxed">
                        {{ $estudio->resultado ?: 'Sin informe o notas adicionales.' }}
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Fechas de Control de Sistema --}}
            <div class="flex justify-between items-center text-xs text-gray-400 font-mono">
                <span>ID Registro: #{{ $estudio->id }}</span>
                <span>Última modificación: {{ $estudio->updated_at->format('d/m/Y H:i') }} hs</span>
            </div>

            {{-- Footer de Acciones --}}
            <div class="flex justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('estudios_medicos.index') }}"
                    class="btn btn-outline-danger px-5 py-2 rounded shadow-sm no-underline text-sm font-medium">
                    Volver al Listado
                </a>
                <a href="{{ route('estudios_medicos.edit', $estudio->id) }}"
                    class="bg-gradient-to-r from-[#1B7D8F] to-[#2BA8A0] text-white px-6 py-2 rounded-xl font-semibold shadow-md hover:scale-105 transition duration-300 no-underline text-sm">
                    Editar Registro
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
@endpush
