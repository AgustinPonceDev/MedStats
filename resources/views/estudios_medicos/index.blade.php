@extends('layouts.app')
@section('title', 'Gestor de Estudios Médicos')
@section('contenido')
    <div class="w-100" style="padding-left: 0; margin-left: 0;">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#1B7D8F]">
                    Gestor de Diagnóstico por Imágenes
                </h1>
                <p class="text-gray-500 mt-1">Control de estudios de Rayos, Tomografías, uso de contraste e insumos.</p>
            </div>
            <a href="{{ route('estudios_medicos.create') }}"
                class="group flex items-center gap-2 bg-[#1B7D8F] hover:bg-[#156370] text-white font-semibold py-2.5 px-6 rounded-xl shadow-lg shadow-[#1B7D8F]/20 transition-all duration-300 transform hover:-translate-y-0.5 no-underline">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                <span>Nuevo Estudio / Práctica</span>
            </a>
        </div>

        {{-- Filtros y Controles --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
            <div class="flex flex-col lg:flex-row gap-5 justify-between items-end lg:items-center">

                {{-- Filtros de Fecha --}}
                <div class="flex flex-wrap items-end gap-4 w-full lg:w-auto">
                    <div class="w-full sm:w-auto">
                        <label for="fechaDesde"
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Desde</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </div>
                            <input type="date" id="fechaDesde"
                                class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#1B7D8F] focus:border-[#1B7D8F] block w-full transition-colors">
                        </div>
                    </div>

                    <div class="w-full sm:w-auto">
                        <label for="fechaHasta"
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Hasta</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </div>
                            <input type="date" id="fechaHasta"
                                class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#1B7D8F] focus:border-[#1B7D8F] block w-full transition-colors">
                        </div>
                    </div>

                    <button id="limpiarFechas"
                        class="px-4 py-2 bg-white border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 hover:text-[#1B7D8F] hover:border-[#1B7D8F] transition-all flex items-center gap-2 h-[38px]">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Limpiar
                    </button>
                </div>

                {{-- Buscador Global --}}
                <div class="w-full lg:w-72">
                    <label for="customSearch"
                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Buscar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" id="customSearch" placeholder="Paciente, DNI, Insumos..."
                            class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#1B7D8F] focus:border-[#1B7D8F] block w-full transition-colors">
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla de Resultados --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="miTabla" class="w-full text-xs text-left text-gray-500" style="width:100%">
                    <thead class="bg-[#1B7D8F] text-white uppercase text-[11px] tracking-wider">
                        <tr>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Fecha</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Apellido y Nombre</th>
                            <th class="px-3 py-4 font-semibold text-center">I-A</th>
                            <th class="px-3 py-4 font-semibold">DNI</th>
                            <th class="px-3 py-4 font-semibold">Estudio Real</th>
                            <th class="px-3 py-4 font-semibold">Regiones</th>
                            <th class="px-3 py-4 font-semibold text-center">Cont 50ml</th>
                            <th class="px-3 py-4 font-semibold text-center">Cont 100ml</th>
                            <th class="px-3 py-4 font-semibold text-center">Jer. Prel.</th>
                            <th class="px-3 py-4 font-semibold">Descartables</th>
                            <th class="px-3 py-4 font-semibold">Otros y Agujas</th>
                            <th class="px-3 py-4 font-semibold text-center no-print">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($estudios as $estudio)
                            <tr class="hover:bg-gray-50 transition-colors">
                                {{-- Fecha --}}
                                <td class="px-3 py-3.5 font-medium text-gray-900 whitespace-nowrap"
                                    data-fecha="{{ $estudio->fecha->format('Y-m-d') }}">
                                    {{ $estudio->fecha->format('d/m/Y') }}
                                </td>
                                {{-- Apellido y Nombre --}}
                                <td class="px-3 py-3.5 font-semibold text-[#1B7D8F] whitespace-nowrap">
                                    {{ optional($estudio->paciente)->apellido }}, {{ optional($estudio->paciente)->nombre }}
                                </td>
                                {{-- I-A --}}
                                <td class="px-3 py-3.5 text-center">
                                    <div class="inline-flex items-center justify-center"
                                        title="{{ $estudio->ia == 'I' ? 'Internado' : 'Ambulatorio' }}">
                                        <div class="relative w-9 h-5 rounded-full transition-colors {{ $estudio->ia == 'I' ? 'bg-amber-400' : 'bg-blue-200' }}">
                                            <div class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow transition-all {{ $estudio->ia == 'I' ? 'left-4' : 'left-0.5' }}"></div>
                                        </div>
                                        <span class="ml-1.5 text-[10px] font-bold {{ $estudio->ia == 'I' ? 'text-amber-700' : 'text-blue-700' }}">
                                            {{ $estudio->ia ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                {{-- DNI --}}
                                <td class="px-3 py-3.5 font-mono text-gray-600">
                                    {{ optional($estudio->paciente)->dni ?? '-' }}
                                </td>
                                {{-- Estudio Real --}}
                                <td class="px-3 py-3.5 font-medium text-gray-800 whitespace-nowrap">
                                    {{ $estudio->tipo_estudio }}
                                </td>
                                {{-- Regiones --}}
                                <td class="px-3 py-3.5 text-gray-600 max-w-[150px] truncate" title="{{ $estudio->regiones }}">
                                    {{ $estudio->regiones ?? '-' }}
                                </td>
                                {{-- Cont 50ml --}}
                                <td class="px-3 py-3.5 text-center font-bold text-gray-700">
                                    {{ $estudio->cont_50ml ?: '-' }}
                                </td>
                                {{-- Cont 100ml --}}
                                <td class="px-3 py-3.5 text-center font-bold text-gray-700">
                                    {{ $estudio->cont_100ml ?: '-' }}
                                </td>
                                {{-- Jeringa Prellena --}}
                                <td class="px-3 py-3.5 text-center font-bold text-gray-700">
                                    {{ $estudio->jeringa_prellenada ?: '-' }}
                                </td>
                                {{-- Descartables --}}
                                <td class="px-3 py-3.5 text-gray-600 max-w-[120px] truncate" title="{{ $estudio->descartables }}">
                                    {{ $estudio->descartables ?? '-' }}
                                </td>
                                {{-- Otros y agujas de punción --}}
                                <td class="px-3 py-3.5 text-gray-600 max-w-[150px] truncate" title="{{ $estudio->otros_agujas }}">
                                    {{ $estudio->otros_agujas ?? '-' }}
                                </td>
                                {{-- Acciones --}}
                                <td class="px-3 py-3.5 text-center no-print">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('estudios_medicos.show', $estudio->id) }}"
                                            class="p-1 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors"
                                            title="Ver Detalle">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </a>
                                        <a href="{{ route('estudios_medicos.edit', $estudio->id) }}"
                                            class="p-1 bg-amber-50 text-amber-600 rounded hover:bg-amber-100 transition-colors"
                                            title="Editar">
                                            <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                        </a>
                                        <form action="{{ route('estudios_medicos.destroy', $estudio->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('¿Está seguro de eliminar este registro clínico?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1 bg-red-50 text-red-600 rounded hover:bg-red-100 transition-colors border-none cursor-pointer">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-6 py-10 text-center text-gray-400">
                                    <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                    No se encontraron registros de diagnóstico por imágenes.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white; }
        .card, .shadow-sm { box-shadow: none !important; border: none !important; }
    }

    /* CORRECCIÓN PARA EL ANCHO DE LA TABLA */
    .dataTables_wrapper {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    #miTabla {
        width: 100% !important;
        margin: 0 !important;
    }

    /* Ocultar controles default de DataTables */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter { display: none !important; }

    /* Eliminar líneas superior e inferior de la tabla */
    table.dataTable.no-footer {
        border-top: none !important;
        border-bottom: none !important;
    }

    /* Botón actual (página seleccionada) */
    #miTabla_wrapper .dataTables_paginate .paginate_button.current,
    #miTabla_wrapper .dataTables_paginate .paginate_button.current:hover,
    #miTabla_wrapper .dataTables_paginate .paginate_button.current:active {
        background: #32989D !important;   /* fondo teal */
        color: #ffffff !important;        /* texto blanco */
        border: none !important;
        border-radius: 0.5rem !important;
    }

    /* Botones normales */
    #miTabla_wrapper .dataTables_paginate .paginate_button {
        background: #f9fafb !important;   /* gris claro */
        color: #374151 !important;        /* gris oscuro */
        border: none !important;
        border-radius: 0.5rem !important;
    }

    /* Hover en botones normales */
    #miTabla_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e5e7eb !important;   /* gris medio */
        color: #111827 !important;        /* casi negro */
        border: none !important;
        border-radius: 0.5rem !important;
    }

    /* Estado activo (cuando se hace clic) */
    #miTabla_wrapper .dataTables_paginate .paginate_button:active {
        background: #25636d !important;   /* teal más oscuro */
        color: #ffffff !important;        /* texto blanco */
        border: none !important;
        border-radius: 0.5rem !important;
    }
</style>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.lucide) lucide.createIcons();

        const idiomaEspanol = {
            processing: "Procesando...",
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
            infoFiltered: "(filtrado de un total de _MAX_ registros)",
            loadingRecords: "Cargando...",
            zeroRecords: "No se encontraron resultados",
            emptyTable: "Ningún dato disponible en esta tabla",
            paginate: {
                first: "Primero",
                previous: "Anterior",
                next: "Siguiente",
                last: "Último"
            }
        };

        const tabla = $('#miTabla').DataTable({
            dom: 'rt<"flex items-center justify-between px-6 py-3"ip>',
            language: idiomaEspanol,
            pageLength: 10,
            order: [[0, 'desc']],
            drawCallback: function() {
                if (window.lucide) lucide.createIcons();
            }
        });

        // ... resto del código igual (no cambies nada más)
    });
</script>
@endpush