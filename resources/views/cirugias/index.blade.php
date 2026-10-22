@extends('layouts.app')
@section('title', 'Gestión de Cirugías')
@section('contenido')
    <div class="w-100" style="padding-left: 0; margin-left: 0;">

        <!-- Header con Título y Botón de Crear -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-[#1B7D8F]">
                    Gestor de Cirugías
                </h1>
                <p class="text-gray-500 mt-1">Administra, filtra y exporta el registro histórico de cirugías.</p>
            </div>
            <a href="{{ route('cirugias.create') }}"
                class="group flex items-center gap-2 bg-[#1B7D8F] hover:bg-[#156370] text-white font-semibold py-2.5 px-6 rounded-xl shadow-lg shadow-[#1B7D8F]/20 transition-all duration-300 transform hover:-translate-y-0.5">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                <span>Nueva Cirugía</span>
            </a>
        </div>

        <!-- Tarjeta de Filtros y Controles -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
            <div class="flex flex-col lg:flex-row gap-5 justify-between items-end lg:items-center">

                <!-- Filtros de Fecha -->
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

                <!-- Buscador Global -->
                <div class="w-full lg:w-72">
                    <label for="customSearch"
                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Buscar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </div>
                        <input type="text" id="customSearch" placeholder="Paciente, DNI, Cirujano..."
                            class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#1B7D8F] focus:border-[#1B7D8F] block w-full transition-colors">
                    </div>
                </div>
            </div>
        </div>

        @php
            $nombreCompleto = fn($e) => $e ? trim(($e->nombre ?? '') . ' ' . ($e->apellido ?? '')) : '';
        @endphp
        <!-- Tabla de Resultados -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="miTabla" class="w-full text-xs text-left text-gray-500" style="width:100%">
                    <thead class="bg-[#1B7D8F] text-white uppercase text-[11px] tracking-wider">
                        <tr>
                            <th class="px-3 py-4 w-8 no-print"></th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Fecha</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Quirófano</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Paciente</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Procedimiento</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Cirujano</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Anestesia</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Estado</th>
                            <th class="px-3 py-4 font-semibold text-center no-print">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($cirugias as $cirugia)
                            @php
                                $pac = $cirugia->get_paciente;
                                $edad = optional($pac)->fecha_nacimiento
                                    ? \Carbon\Carbon::parse($pac->fecha_nacimiento)->age
                                    : null;
                                $fechaC = \Carbon\Carbon::parse($cirugia->fecha_cirugia);
                                $horaC = $cirugia->hora_cirugia
                                    ? \Carbon\Carbon::parse($cirugia->hora_cirugia)->format('H:i')
                                    : '';
                                $sus = (bool) $cirugia->suspendida;
                                $exp = [
                                    'fecha' => $fechaC->format('d/m/Y'),
                                    'hora' => $horaC,
                                    'quirofano' => $cirugia->get_quirofano->nombre ?? '-',
                                    'edad' => $edad ?? '',
                                    'dni' => $pac->dni ?? '',
                                    'paciente' => $nombreCompleto($pac),
                                    'procedimiento' => $cirugia->get_procedimiento->nombre_procedimiento ?? '',
                                    'cirujano' => $nombreCompleto($cirugia->get_cirujano),
                                    'ayudante1' => $nombreCompleto($cirugia->get_ayudante1),
                                    'ayudante2' => $nombreCompleto($cirugia->get_ayudante2),
                                    'anestesiologo' => $nombreCompleto($cirugia->get_anestesista),
                                    'instrumentador' => $nombreCompleto($cirugia->get_instrumentador),
                                    'enfermero' => $nombreCompleto($cirugia->get_enfermero),
                                    'anestesia' => optional($cirugia->get_tipo_anestesia)->nombre ?? '',
                                    'urgencia' => $cirugia->urgencia ? 'Sí' : 'No',
                                    'obito' => $cirugia->obito ? 'Sí' : 'No',
                                    'suspendida' => $sus ? 'Sí' : 'No',
                                ];
                            @endphp
                            <tr class="{{ $sus ? 'bg-gray-200 text-gray-500 hover:bg-gray-250 opacity-75' : 'hover:bg-gray-50 transition-colors' }}"
                                data-fecha="{{ $cirugia->fecha_cirugia }}" data-export='@json($exp)'>
                                {{-- Desplegable del equipo --}}
                                <td class="px-3 py-3.5 no-print">
                                    <button type="button"
                                        class="toggle-equipo p-1 rounded text-gray-400 hover:text-[#1B7D8F] hover:bg-gray-100 transition-colors"
                                        title="Ver equipo quirúrgico" aria-expanded="false">
                                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                    </button>
                                    <span class="hidden">{{ $exp['ayudante1'] }} {{ $exp['ayudante2'] }}
                                        {{ $exp['anestesiologo'] }} {{ $exp['instrumentador'] }}
                                        {{ $exp['enfermero'] }}</span>
                                </td>
                                {{-- Fecha y hora --}}
                                <td class="px-3 py-3.5 whitespace-nowrap"
                                    data-order="{{ $fechaC->format('Y-m-d') }} {{ $horaC }}">
                                    <div class="font-medium {{ $sus ? 'text-gray-600' : 'text-gray-900' }}">
                                        {{ $fechaC->format('d/m/Y') }}</div>
                                    @if ($horaC)
                                        <div class="text-[11px] text-gray-400">{{ $horaC }}</div>
                                    @endif
                                </td>
                                {{-- Quirófano --}}
                                <td class="px-3 py-3.5 whitespace-nowrap">{{ $exp['quirofano'] }}</td>
                                {{-- Paciente + DNI / edad --}}
                                <td class="px-3 py-3.5 whitespace-nowrap">
                                    <div class="font-medium {{ $sus ? 'text-gray-700' : 'text-[#1B7D8F]' }}">
                                        {{ $exp['paciente'] }}</div>
                                    <div class="text-[11px] text-gray-400 font-mono">
                                        DNI {{ $exp['dni'] }}@if ($edad !== null)
                                            · {{ $edad }} años
                                        @endif
                                    </div>
                                </td>
                                {{-- Procedimiento --}}
                                <td class="px-3 py-3.5 max-w-[220px] truncate" title="{{ $exp['procedimiento'] }}">
                                    {{ $exp['procedimiento'] }}</td>
                                {{-- Cirujano --}}
                                <td class="px-3 py-3.5 whitespace-nowrap">{{ $exp['cirujano'] }}</td>
                                {{-- Anestesia --}}
                                <td class="px-3 py-3.5 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-medium {{ $sus ? 'bg-gray-300 text-gray-700' : 'bg-blue-50 text-blue-600' }} rounded-full">
                                        {{ $exp['anestesia'] ?: '-' }}
                                    </span>
                                </td>
                                {{-- Estado --}}
                                <td class="px-3 py-3.5">
                                    <div class="flex flex-wrap gap-1">
                                        @if ($sus)
                                            <span
                                                class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-300 text-gray-700">Suspendida</span>
                                        @endif
                                        @if ($cirugia->urgencia)
                                            <span
                                                class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-red-50 text-red-600">Urgencia</span>
                                        @endif
                                        @if ($cirugia->obito)
                                            <span
                                                class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-600">Óbito</span>
                                        @endif
                                        @if (!$sus && !$cirugia->urgencia && !$cirugia->obito)
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </div>
                                </td>
                                {{-- Acciones --}}
                                <td class="px-3 py-3.5 text-center no-print">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('cirugias.show', $cirugia) }}"
                                            class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                            title="Ver Detalles">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('cirugias.medicamentos', $cirugia) }}"
                                            class="p-1.5 bg-[#e6f4f3] text-[#1B7D8F] rounded-lg hover:bg-[#cdeceb] transition-colors"
                                            title="Cargar Medicamentos">
                                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('cirugias.edit', $cirugia) }}"
                                            class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors"
                                            title="Editar">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <i data-lucide="inbox" class="w-10 h-10 opacity-50"></i>
                                            <p>No hay cirugías registradas aún.</p>
                                        </div>
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

            <style>
                @page {
                    size: landscape;
                }

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

                .toggle-equipo svg {
                    transition: transform 0.2s ease;
                }

                .toggle-equipo.abierto svg {
                    transform: rotate(90deg);
                }

                .toggle-equipo.abierto {
                    color: #1B7D8F;
                    background: #f3f4f6;
                }
            </style>
        @endsection

        @push('scripts')
            <script src="{{ asset('vendor/js/jspdf.umd.min.js') }}"></script>
            <script src="{{ asset('vendor/js/jspdf.plugin.autotable.min.js') }}"></script>
            <script src="{{ asset('vendor/js/jquery-3.6.0.min.js') }}"></script>
            <script src="{{ asset('vendor/js/dataTables.min.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('vendor/css/jquery.dataTables.min.css') }}">
            <script src="{{ asset('vendor/js/xlsx.full.min.js') }}"></script>

            <script>
                $(document).ready(function() {
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
                        order: [],
                        columnDefs: [{
                            orderable: false,
                            targets: [0, 7, 8]
                        }],
                        drawCallback: function() {
                            if (window.lucide) lucide.createIcons();
                        }
                    });

                    tabla.on('draw', function() {
                        tabla.rows().every(function() {
                            this.child.hide();
                        });
                        $('#miTabla .toggle-equipo').removeClass('abierto').attr('aria-expanded', 'false');
                    });

                    const esc = (t) => String(t ?? '').replace(/[&<>"']/g, c => ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    } [c]));

                    function htmlEquipo(d) {
                        const item = (titulo, valor) =>
                            `<div><p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">${titulo}</p>` +
                            `<p class="mt-0.5 text-xs font-medium text-gray-700">${esc(valor || '—')}</p></div>`;
                        return `<div class="grid grid-cols-2 md:grid-cols-5 gap-4 px-6 py-3 bg-gray-50">` +
                            item('Ayudante 1', d.ayudante1) +
                            item('Ayudante 2', d.ayudante2) +
                            item('Anestesiólogo', d.anestesiologo) +
                            item('Instrumentador', d.instrumentador) +
                            item('Enfermero', d.enfermero) +
                            `</div>`;
                    }

                    $('#miTabla tbody').on('click', '.toggle-equipo', function() {
                        const boton = $(this);
                        const tr = boton.closest('tr');
                        const fila = tabla.row(tr);
                        if (fila.child.isShown()) {
                            fila.child.hide();
                            boton.removeClass('abierto').attr('aria-expanded', 'false');
                        } else {
                            fila.child(htmlEquipo(tr.data('export')), 'p-0').show();
                            boton.addClass('abierto').attr('aria-expanded', 'true');
                        }
                    });

                    $('#customSearch').on('keyup', function() {
                        tabla.search(this.value).draw();
                    });

                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                        if (settings.nTable.id !== 'miTabla') return true;
                        const fechaDesde = $('#fechaDesde').val();
                        const fechaHasta = $('#fechaHasta').val();
                        const fechaTexto = $(tabla.row(dataIndex).node()).data('fecha');

                        if (!fechaTexto) return true;

                        const fechaCirugia = new Date(fechaTexto);
                        const desde = fechaDesde ? new Date(fechaDesde) : null;
                        const hasta = fechaHasta ? new Date(fechaHasta) : null;

                        if (desde) desde.setHours(0, 0, 0, 0);
                        if (hasta) hasta.setHours(23, 59, 59, 999);
                        if (fechaCirugia) fechaCirugia.setHours(12, 0, 0, 0);

                        return (!desde || fechaCirugia >= desde) && (!hasta || fechaCirugia <= hasta);
                    });

                    $('#fechaDesde, #fechaHasta').on('change', function() {
                        tabla.draw();
                    });

                    $('#limpiarFechas').on('click', function() {
                        $('#fechaDesde').val('');
                        $('#fechaHasta').val('');
                        $('#customSearch').val('');
                        tabla.search('').draw();
                    });
                });

                // --- OBTENER DATOS DE EXPORTACIÓN DIRECTAMENTE DESDE EL ATRIBUTO DATA-EXPORT ---
                function filasParaExportar() {
                    const alcance = document.getElementById('alcanceExport')?.value || 'pagina';
                    const opciones = alcance === 'todo' ? {
                        search: 'applied'
                    } : {
                        search: 'applied',
                        page: 'current'
                    };

                    // Mapeamos los nodos tr filtrados para extraer el objeto JSON completo guardado en data-export
                    const nodos = $('#miTabla').DataTable().rows(opciones).nodes();
                    return $(nodos).map(function() {
                        return $(this).data('export');
                    }).get();
                }

                const escHtml = (t) => String(t ?? '').replace(/[&<>"']/g, c => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                } [c]));

                // Cabecera completa para impresión y PDF extendido
                const CABECERA_PRINT = [
                    'Fecha', 'Hora', 'Quirófano', 'Edad', 'DNI', 'Paciente',
                    'Procedimiento', 'Cirujano', 'Ayudante 1', 'Ayudante 2',
                    'Anestesiólogo', 'Instrumentador', 'Enfermero', 'Anestesia', 'Urgencia', 'Óbito'
                ];

                // --- 1. IMPRIMIR TABLA COMPLETA ---
                function imprimirTablaCompleta() {
                    const registros = filasParaExportar();
                    const filas = registros.map(d => [
                        d.fecha, d.hora, d.quirofano, d.edad, d.dni, d.paciente,
                        d.procedimiento, d.cirujano, d.ayudante1, d.ayudante2,
                        d.anestesiologo, d.instrumentador, d.enfermero, d.anestesia, d.urgencia, d.obito
                    ]);

                    const ventana = window.open('', '', 'width=900,height=700');
                    ventana.document.write(`
                    <html>
                    <head>
                        <title>Libro de Cirugías</title>
                        <style>
                            @page { size: landscape; }
                            body { font-family: Arial, sans-serif; margin: 20px; }
                            table { width: 100%; border-collapse: collapse; font-size: 10px; }
                            th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
                            th { background-color: #1B7D8F; color: white; font-weight: bold; }
                            h2 { color: #1B7D8F; }
                        </style>
                    </head>
                    <body>
                        <h2>Reporte de Cirugías</h2>
                        <table>
                            <thead><tr>${CABECERA_PRINT.map(c => `<th>${c}</th>`).join('')}</tr></thead>
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

                    const headers = [
                        ['Fecha', 'Hora', 'N°Q', 'Paciente', 'DNI', 'Procedimiento', 'Cirujano', 'Anestesiólogo',
                            'Anestesia'
                        ]
                    ];
                    const body = filasParaExportar().map(d => [
                        d.fecha, d.hora, d.quirofano, d.paciente, d.dni,
                        d.procedimiento, d.cirujano, d.anestesiologo, d.anestesia
                    ]);

                    doc.text("Reporte de Cirugías", 14, 20);
                    doc.autoTable({
                        head: headers,
                        body: body,
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
                    doc.save('cirugias.pdf');
                }

                // --- 3. EXPORTAR EXCEL CON ANCHO AUTOMÁTICO ---
                document.getElementById('btnExportarExcel').addEventListener('click', function() {
                    const dataExport = filasParaExportar().map(d => ({
                        Fecha: d.fecha,
                        Hora: d.hora,
                        Quirófano: d.quirofano,
                        Edad: d.edad,
                        DNI: d.dni,
                        Paciente: d.paciente,
                        Procedimiento: d.procedimiento,
                        Cirujano: d.cirujano,
                        Ayudante_1: d.ayudante1,
                        Ayudante_2: d.ayudante2,
                        Anestesiólogo: d.anestesiologo,
                        Instrumentador: d.instrumentador,
                        Enfermero: d.enfermero,
                        Anestesia: d.anestesia,
                        Urgencia: d.urgencia,
                        Óbito: d.obito,
                        Suspendida: d.suspendida
                    }));

                    const ws = XLSX.utils.json_to_sheet(dataExport);

                    // --- LÓGICA PARA AUTOAJUSTAR EL ANCHO DE LAS COLUMNAS ---
                    const colWidths = [];
                    dataExport.forEach(row => {
                        Object.keys(row).forEach((key, i) => {
                            const valorCelda = row[key] ? String(row[key]) : '';
                            const longitudTexto = Math.max(valorCelda.length, key.length);
                            colWidths[i] = Math.max(colWidths[i] || 10, longitudTexto + 3);
                        });
                    });

                    // Asignamos los anchos calculados a la hoja de Excel
                    ws['!cols'] = colWidths.map(w => ({
                        wch: w
                    }));
                    // --------------------------------------------------------

                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, "Cirugias");
                    XLSX.writeFile(wb, "reporte_cirugias.xlsx");
                });
            </script>
        @endpush
