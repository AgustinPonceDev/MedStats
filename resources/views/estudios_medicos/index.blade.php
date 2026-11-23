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

                    <div class="w-full sm:w-auto">
                        <label for="filtroIA"
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Ámbito</label>
                        <select id="filtroIA"
                            class="px-3 py-2 bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#1B7D8F] focus:border-[#1B7D8F] block w-full h-[38px] transition-colors">
                            <option value="">Todos</option>
                            <option value="I">Internados</option>
                            <option value="A">Ambulatorios</option>
                        </select>
                    </div>

                    <div class="w-full sm:w-auto">
                        <label for="filtroUrgencia"
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Urgencia</label>
                        <select id="filtroUrgencia"
                            class="px-3 py-2 bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#1B7D8F] focus:border-[#1B7D8F] block w-full h-[38px] transition-colors">
                            <option value="">Todos</option>
                            <option value="1">Guardia (urgentes)</option>
                            <option value="0">Programados</option>
                        </select>
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
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Hora</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Apellido y Nombre</th>
                            <th class="px-3 py-4 font-semibold text-center">I-A</th>
                            <th class="px-3 py-4 font-semibold text-center">Urgencia</th>
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
                            @php
                                // Resumen de lo que se devuelve al stock si se elimina el estudio
                                $insumosDev = collect([
                                    ['Contraste 50ml', (int) $estudio->cont_50ml],
                                    ['Contraste 100ml', (int) $estudio->cont_100ml],
                                    ['Jeringa prellenada', (int) $estudio->jeringa_prellenada],
                                ])
                                    ->filter(fn($i) => $i[1] > 0)
                                    ->map(fn($i) => $i[1] . ' × ' . $i[0])
                                    ->implode(', ');
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors" data-ia="{{ $estudio->ia }}"
                                data-urgencia="{{ $estudio->urgencia ? 1 : 0 }}">
                                {{-- Fecha --}}
                                <td class="px-3 py-3.5 font-medium text-gray-900 whitespace-nowrap"
                                    data-fecha="{{ $estudio->fecha->format('Y-m-d') }}"
                                    data-order="{{ $estudio->fecha->format('Y-m-d') }} {{ $estudio->hora_estudio ? \Carbon\Carbon::parse($estudio->hora_estudio)->format('H:i') : '00:00' }}">
                                    {{ $estudio->fecha->format('d/m/Y') }}
                                </td>
                                {{-- Hora --}}
                                <td class="px-3 py-3.5 text-gray-600 whitespace-nowrap"
                                    data-order="{{ $estudio->fecha->format('Y-m-d') }} {{ $estudio->hora_estudio ? \Carbon\Carbon::parse($estudio->hora_estudio)->format('H:i') : '00:00' }}">
                                    {{ $estudio->hora_estudio ? \Carbon\Carbon::parse($estudio->hora_estudio)->format('H:i') : '-' }}
                                </td>
                                {{-- Apellido y Nombre --}}
                                <td class="px-3 py-3.5 font-semibold text-[#1B7D8F] whitespace-nowrap">
                                    {{ optional($estudio->paciente)->apellido }},
                                    {{ optional($estudio->paciente)->nombre }}
                                </td>
                                {{-- I-A --}}
                                <td class="px-3 py-3.5 text-center">
                                    <div class="inline-flex items-center justify-center"
                                        title="{{ $estudio->ia == 'I' ? 'Internado' : 'Ambulatorio' }}">
                                        <div
                                            class="relative w-9 h-5 rounded-full transition-colors {{ $estudio->ia == 'I' ? 'bg-amber-400' : 'bg-blue-200' }}">
                                            <div
                                                class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow transition-all {{ $estudio->ia == 'I' ? 'left-4' : 'left-0.5' }}">
                                            </div>
                                        </div>
                                        <span
                                            class="ml-1.5 text-[10px] font-bold {{ $estudio->ia == 'I' ? 'text-amber-700' : 'text-blue-700' }}">
                                            {{ $estudio->ia ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                {{-- Urgencia --}}
                                <td class="px-3 py-3.5 text-center whitespace-nowrap"
                                    data-order="{{ $estudio->urgencia ? 1 : 0 }}">
                                    @if ($estudio->urgencia)
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200"
                                            title="Pedido desde Guardia">Guardia</span>
                                    @else
                                        <span class="text-[10px] font-semibold text-gray-400">Programado</span>
                                    @endif
                                </td>
                                {{-- DNI --}}
                                <td class="px-3 py-3.5 font-mono text-gray-600">
                                    {{ optional($estudio->paciente)->dni ?? '-' }}
                                </td>
                                {{-- Estudio Real --}}
                                <td class="px-3 py-3.5 font-medium text-gray-800 whitespace-nowrap">
                                    {{ optional($estudio->estudio)->nombre ?? '-' }}
                                </td>
                                {{-- Regiones --}}
                                <td class="px-3 py-3.5 text-gray-600 max-w-[150px] truncate"
                                    title="{{ $estudio->regiones }}">
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
                                <td class="px-3 py-3.5 text-gray-600 max-w-[120px] truncate"
                                    title="{{ $estudio->descartables }}">
                                    {{ $estudio->descartables ?? '-' }}
                                </td>
                                {{-- Otros y agujas de punción --}}
                                <td class="px-3 py-3.5 text-gray-600 max-w-[150px] truncate"
                                    title="{{ $estudio->otros_agujas }}">
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
                                        <form action="{{ route('estudios_medicos.destroy', $estudio->id) }}"
                                            method="POST" class="inline" onsubmit="return confirmarEliminacion(this);"
                                            data-estudio="{{ optional($estudio->estudio)->nombre ?? $estudio->tipo_estudio }}"
                                            data-paciente="{{ optional($estudio->paciente)->apellido }}, {{ optional($estudio->paciente)->nombre }}"
                                            data-fecha="{{ $estudio->fecha->format('d/m/Y') }}"
                                            data-insumos="{{ $insumosDev }}">
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
                                <td colspan="14" class="px-6 py-10 text-center text-gray-400">
                                    <i data-lucide="alert-circle" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                    No se encontraron registros de diagnóstico por imágenes.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="mt-6 flex flex-wrap gap-3 justify-start">
            <select id="alcanceExport"
                class="px-3 py-2 bg-white border border-gray-200 text-gray-700 text-sm rounded-xl shadow-sm focus:ring-[#1B7D8F] focus:border-[#1B7D8F]">
                <option value="pagina">Solo esta página</option>
                <option value="todo">Todo lo filtrado</option>
            </select>
            <button onclick="imprimirTablaCompleta()"
                class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 hover:text-[#1B7D8F] transition-all shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Imprimir Tabla</span>
            </button>
            <button onclick="exportarFiltradoPDF()"
                class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-red-600 rounded-xl hover:bg-red-50 transition-all shadow-sm">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span>Exportar PDF</span>
            </button>
            <button id="btnExportarExcel"
                class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-green-600 rounded-xl hover:bg-green-50 transition-all shadow-sm">
                <i data-lucide="sheet" class="w-4 h-4"></i>
                <span>Exportar Excel</span>
            </button>
        </div>

    </div>

    {{-- Aviso propio para eliminar un estudio --}}
    <div id="modalEliminar" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4" role="dialog"
        aria-modal="true" aria-labelledby="modalElimTitulo">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 shrink-0 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 6h18" />
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                        <line x1="10" x2="10" y1="11" y2="17" />
                        <line x1="14" x2="14" y1="11" y2="17" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 id="modalElimTitulo" class="text-base font-bold text-gray-800">Eliminar estudio</h3>
                    <p class="mt-1 text-sm text-gray-500">Esta acción no se puede deshacer.</p>
                    <div class="mt-3 rounded-lg bg-gray-50 border border-gray-100 px-3 py-2 text-sm">
                        <div id="modalElimEstudio" class="font-semibold text-gray-800"></div>
                        <div id="modalElimPaciente" class="text-gray-500"></div>
                    </div>
                    <p id="modalElimStock" class="mt-3 text-sm text-gray-600 leading-relaxed"></p>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" id="modalElimCancel"
                    class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Cancelar</button>
                <button type="button" id="modalElimOk"
                    class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition">Sí,
                    eliminar</button>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }

            .card,
            .shadow-sm {
                box-shadow: none !important;
                border: none !important;
            }

            aside,
            nav {
                display: none !important;
            }

            main {
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        .dataTables_wrapper {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #miTabla {
            width: 100% !important;
            margin: 0 !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            display: none !important;
        }

        table.dataTable.no-footer {
            border-top: none !important;
            border-bottom: none !important;
        }

        #miTabla_wrapper .dataTables_info {
            color: #6b7280 !important;
            font-size: 0.875rem !important;
            padding-top: 0 !important;
        }

        #miTabla_wrapper .dataTables_paginate .paginate_button {
            background: #f9fafb !important;
            color: #374151 !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.35rem 0.75rem !important;
            margin: 0 0.2rem !important;
            cursor: pointer;
        }

        #miTabla_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e5e7eb !important;
            color: #111827 !important;
            border: none !important;
        }

        #miTabla_wrapper .dataTables_paginate .paginate_button.current {
            background: #32989D !important;
            color: #ffffff !important;
            border: none !important;
        }
    </style>
@endsection

@push('scripts')
    <!-- jsPDF y autoTable -->
    <script src="{{ asset('vendor/js/jspdf.umd.min.js') }}"></script>
    <script src="{{ asset('vendor/js/jspdf.plugin.autotable.min.js') }}"></script>
    <script src="{{ asset('vendor/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('vendor/js/dataTables.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('vendor/css/jquery.dataTables.min.css') }}">
    <!-- SheetJS para generar archivos Excel -->
    <script src="{{ asset('vendor/js/xlsx.full.min.js') }}"></script>

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
                order: [
                    [1, 'desc']
                ],
                columnDefs: [{
                        orderable: true,
                        targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                    },
                    {
                        orderable: false,
                        targets: [13]
                    }
                ],
                drawCallback: function() {
                    if (window.lucide) lucide.createIcons();
                }
            });

            $('#customSearch').on('keyup', function() {
                tabla.search(this.value).draw();
            });

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const fechaDesde = $('#fechaDesde').val();
                const fechaHasta = $('#fechaHasta').val();
                const rowNode = tabla.row(dataIndex).node();
                const fechaTexto = $(rowNode).find('td').eq(0).data('fecha');

                if (!fechaTexto) return true;

                const fechaEstudio = new Date(fechaTexto);
                const desde = fechaDesde ? new Date(fechaDesde) : null;
                const hasta = fechaHasta ? new Date(fechaHasta) : null;

                if (desde) desde.setHours(0, 0, 0, 0);
                if (hasta) hasta.setHours(23, 59, 59, 999);
                if (fechaEstudio) fechaEstudio.setHours(12, 0, 0, 0);

                return (!desde || fechaEstudio >= desde) && (!hasta || fechaEstudio <= hasta);
            });

            // Filtros por ámbito (Internado / Ambulatorio) y por urgencia (Guardia / Programado)
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'miTabla') return true;
                const fila = $(tabla.row(dataIndex).node());
                const ia = $('#filtroIA').val();
                const urg = $('#filtroUrgencia').val();
                if (ia && String(fila.attr('data-ia')) !== ia) return false;
                if (urg !== '' && String(fila.attr('data-urgencia')) !== urg) return false;
                return true;
            });

            $('#fechaDesde, #fechaHasta, #filtroIA, #filtroUrgencia').on('change', function() {
                tabla.draw();
            });

            $('#limpiarFechas').on('click', function() {
                $('#fechaDesde').val('');
                $('#fechaHasta').val('');
                $('#filtroIA').val('');
                $('#filtroUrgencia').val('');
                $('#customSearch').val('');
                tabla.search('').draw();
            });
        });

        // --- AVISO PROPIO PARA ELIMINAR UN ESTUDIO ---
        let formEliminar = null;

        function confirmarEliminacion(form) {
            const modal = document.getElementById('modalEliminar');
            if (!modal) return confirm('¿Está seguro de eliminar este registro clínico?'); // por si falta el modal

            formEliminar = form;
            document.getElementById('modalElimEstudio').textContent = (form.dataset.estudio || 'Estudio') +
                ' · ' + (form.dataset.fecha || '');
            document.getElementById('modalElimPaciente').textContent = form.dataset.paciente || '';
            document.getElementById('modalElimStock').textContent = form.dataset.insumos ?
                'Se devolverán al stock de Diagnóstico por Imágenes: ' + form.dataset.insumos + '.' :
                'Este estudio no tiene insumos de stock para devolver.';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('modalElimCancel').focus();
            return false; // frena el envío hasta que el usuario acepte
        }

        function cerrarModalEliminar() {
            const modal = document.getElementById('modalEliminar');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            formEliminar = null;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('modalEliminar');
            if (!modal) return;

            document.getElementById('modalElimCancel').addEventListener('click', cerrarModalEliminar);
            document.getElementById('modalElimOk').addEventListener('click', function() {
                if (!formEliminar) return;
                this.disabled = true; // evita doble clic
                formEliminar.submit();
            });
            modal.addEventListener('click', function(e) {
                if (e.target === modal) cerrarModalEliminar();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) cerrarModalEliminar();
            });
        });

        // --- EXTRACCIÓN ROBUSTA DE FILAS DESDE EL DOM ACTUAL ---
        function filasParaExportar() {
            const alcance = document.getElementById('alcanceExport')?.value || 'pagina';
            const dt = $('#miTabla').DataTable();

            // Obtenemos los índices de las filas aplicadas al filtro o página actual
            const opciones = alcance === 'todo' ? {
                search: 'applied'
            } : {
                search: 'applied',
                page: 'current'
            };
            const indices = dt.rows(opciones).indexes();

            let datos = [];
            indices.each(function(idx) {
                const tr = dt.row(idx).node();
                // Tomamos las primeras 13 columnas (excluyendo Acciones) y limpiamos sus etiquetas HTML
                const valoresFila = $(tr).find('td').slice(0, 13).map(function() {
                    return $(this).text().replace(/\s+/g, ' ').trim();
                }).get();
                datos.push(valoresFila);
            });
            return datos;
        }

        const escHtml = (t) => String(t ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [c]));

        const CABECERA_EXPORT = [
            'Fecha', 'Hora', 'Apellido y Nombre', 'I-A', 'Urgencia', 'DNI', 'Estudio Real',
            'Regiones', 'Cont 50ml', 'Cont 100ml', 'Jer. Prel.', 'Descartables', 'Otros y Agujas'
        ];

        // --- 1. IMPRIMIR TABLA COMPLETA ---
        function imprimirTablaCompleta() {
            const filas = filasParaExportar();

            const ventana = window.open('', '', 'width=900,height=700');
            ventana.document.write(`
                <html>
                <head>
                    <title>Estudios Médicos</title>
                    <style>
                        @page { size: landscape; }
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; font-size: 11px; }
                        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
                        th { background-color: #1B7D8F; color: white; font-weight: bold; }
                    </style>
                </head>
                <body>
                    <h2>Reporte de Estudios Médicos</h2>
                    <table>
                        <thead><tr>${CABECERA_EXPORT.map(c => `<th>${c}</th>`).join('')}</tr></thead>
                        <tbody>${filas.map(f => `<tr>${f.map(v => `<td>${escHtml(v)}</td>`).join('')}</tr>`).join('')}</tbody>
                    </table>
                </body>
                </html>
            `);
            ventana.document.close();
            ventana.focus();
            setTimeout(() => {
                ventana.print();
                ventana.close();
            }, 500);
        }

        // --- 2. EXPORTAR PDF ---
        async function exportarFiltradoPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'landscape',
                format: 'legal'
            });

            doc.text("Reporte de Estudios Médicos", 14, 20);
            doc.autoTable({
                head: [CABECERA_EXPORT],
                body: filasParaExportar(),
                startY: 30,
                theme: 'grid',
                styles: {
                    fontSize: 8,
                    cellPadding: 2
                },
                headStyles: {
                    fillColor: [27, 125, 143]
                }
            });
            doc.save('estudios_medicos.pdf');
        }

        // --- 3. EXPORTAR EXCEL CON ANCHO AUTOMÁTICO ---
        document.getElementById('btnExportarExcel').addEventListener('click', function() {
            const datosCrudos = filasParaExportar();

            // Creamos los objetos para la hoja
            const dataExport = datosCrudos.map(f => ({
                Fecha: f[0],
                Hora: f[1],
                Apellido_y_Nombre: f[2],
                IA: f[3],
                Urgencia: f[4],
                DNI: f[5],
                Estudio_Real: f[6],
                Regiones: f[7],
                Contraste_50ml: f[8],
                Contraste_100ml: f[9],
                Jeringa_Prellenada: f[10],
                Descartables: f[11],
                Otros_y_Agujas: f[12]
            }));

            const ws = XLSX.utils.json_to_sheet(dataExport);

            // --- LÓGICA PARA AUTOAJUSTAR EL ANCHO DE LAS COLUMNAS ---
            const colWidths = [];
            // Recorremos las filas para medir la longitud del texto más largo de cada columna
            dataExport.forEach(row => {
                Object.keys(row).forEach((key, i) => {
                    const valorCelda = row[key] ? String(row[key]) : '';
                    const longitudTexto = Math.max(valorCelda.length, key.length);
                    // Damos un margen mínimo de ancho (ej. 10 de ancho base + un rellenito)
                    colWidths[i] = Math.max(colWidths[i] || 10, longitudTexto + 3);
                });
            });

            // Asignamos los anchos calculados a la hoja de Excel
            ws['!cols'] = colWidths.map(w => ({
                wch: w
            }));
            // --------------------------------------------------------

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Estudios Medicos");
            XLSX.writeFile(wb, "reporte_estudios_medicos.xlsx");
        });
    </script>
@endpush
