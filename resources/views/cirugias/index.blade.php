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

        <!-- Tabla de Resultados -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="miTabla" class="w-full text-xs text-left text-gray-500" style="width:100%">
                    <thead class="bg-[#1B7D8F] text-white uppercase text-[11px] tracking-wider">
                        <tr>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Fecha</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Hora</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">N°Q</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Edad</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">DNI</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Paciente</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Procedimiento</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Cirujano</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap no-print">Ayudante 1</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap no-print">Ayudante 2</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Anestesiologo</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Instrumentador</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Enfermero</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap">Anestesia</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap no-print">Urgencia</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap no-print">Óbito</th>
                            <th class="px-3 py-4 font-semibold whitespace-nowrap text-center no-print">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($cirugias as $cirugia)
                            <tr
                                class="{{ $cirugia->suspendida ? 'bg-gray-200 text-gray-500 hover:bg-gray-250 opacity-75' : 'hover:bg-gray-50 transition-colors' }}">
                                <td class="px-3 py-3.5 font-medium {{ $cirugia->suspendida ? 'text-gray-600' : 'text-gray-900' }} whitespace-nowrap"
                                    data-fecha="{{ $cirugia->fecha_cirugia }}">
                                    {{ \Carbon\Carbon::parse($cirugia->fecha_cirugia)->format('d/m/Y') }}
                                </td>
                                <td class="px-3 py-3.5">
                                    {{ $cirugia->hora_cirugia ? \Carbon\Carbon::parse($cirugia->hora_cirugia)->format('H:i') : '' }}
                                </td>
                                <td class="px-3 py-3.5">{{ $cirugia->get_quirofano->nombre ?? '-' }}</td>
                                <td class="px-3 py-3.5">
                                    {{ optional($cirugia->get_paciente)->fecha_nacimiento
                                        ? \Carbon\Carbon::parse($cirugia->get_paciente->fecha_nacimiento)->age
                                        : '—' }}
                                </td>
                                <td class="px-3 py-3.5 font-mono text-xs">{{ $cirugia->get_paciente->dni }}</td>
                                <td
                                    class="px-3 py-3.5 font-medium {{ $cirugia->suspendida ? 'text-gray-700' : 'text-[#1B7D8F]' }}">
                                    {{ $cirugia->get_paciente->nombre }} {{ $cirugia->get_paciente->apellido }}</td>
                                <td class="px-3 py-3.5">{{ $cirugia->get_procedimiento->nombre_procedimiento }}</td>
                                <td class="px-3 py-3.5">{{ $cirugia->get_cirujano->nombre }}
                                    {{ $cirugia->get_cirujano->apellido }}</td>
                                <td class="px-3 py-3.5 no-print">{{ $cirugia->get_ayudante1->nombre ?? '-' }}
                                    {{ $cirugia->get_ayudante1->apellido ?? '' }}</td>
                                <td class="px-3 py-3.5 no-print">{{ optional($cirugia->get_ayudante2)->nombre ?? '-' }}
                                    {{ optional($cirugia->get_ayudante2)->apellido ?? '' }}</td>
                                <td class="px-3 py-3.5">{{ optional($cirugia->get_anestesista)->nombre }}
                                    {{ optional($cirugia->get_anestesista)->apellido }}</td>
                                <td class="px-3 py-3.5">{{ optional($cirugia->get_instrumentador)->nombre }}
                                    {{ optional($cirugia->get_instrumentador)->apellido }}</td>
                                <td class="px-3 py-3.5">{{ optional($cirugia->get_enfermero)->nombre }}
                                    {{ optional($cirugia->get_enfermero)->apellido }}</td>
                                <td class="px-3 py-3.5">
                                    <span
                                        class="px-2 py-1 text-xs font-medium {{ $cirugia->suspendida ? 'bg-gray-300 text-gray-700' : 'bg-blue-50 text-blue-600' }} rounded-full">
                                        {{ optional($cirugia->get_tipo_anestesia)->nombre ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-3 py-3.5 no-print">
                                    @if ($cirugia->urgencia)
                                        <span
                                            class="px-2 py-1 text-xs font-medium {{ $cirugia->suspendida ? 'bg-gray-300 text-gray-700' : 'bg-red-50 text-red-600' }} rounded-full">Sí</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-medium {{ $cirugia->suspendida ? 'bg-gray-300 text-gray-700' : 'bg-green-50 text-green-600' }} rounded-full">No</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3.5 no-print">
                                    @if ($cirugia->obito)
                                        <span
                                            class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">Sí</span>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
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
                                <td colspan="17" class="px-6 py-12 text-center text-gray-400">
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

        /* Eliminar líneas superior e inferior de la tabla */
        table.dataTable.no-footer {
            border-top: none !important;
            border-bottom: none !important;
        }

        /* Paginación y texto de información (igual en todas las tablas) */
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

        #miTabla_wrapper .dataTables_paginate .paginate_button:active {
            background: #25636d !important;
            color: #ffffff !important;
            border: none !important;
        }

        #miTabla_wrapper .dataTables_paginate .paginate_button.current,
        #miTabla_wrapper .dataTables_paginate .paginate_button.current:hover,
        #miTabla_wrapper .dataTables_paginate .paginate_button.current:active {
            background: #32989D !important;
            color: #ffffff !important;
            border: none !important;
        }

        #miTabla_wrapper .dataTables_paginate .paginate_button.disabled,
        #miTabla_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            background: #f9fafb !important;
            color: #9ca3af !important;
            border: none !important;
            cursor: default !important;
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
        $(document).ready(function() {
            // Inicializar Lucide
            if (window.lucide) lucide.createIcons();

            // Objeto de traducción local
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

            // Inicializar DataTables de forma limpia y nativa
            const tabla = $('#miTabla').DataTable({
                // Pinta la tabla y luego inyecta la info y paginación en el div con clases de Tailwind
                dom: 'rt<"flex items-center justify-between px-6 py-3"ip>',
                language: idiomaEspanol,
                pageLength: 10,
                order: [], // Respetar el orden del backend
                drawCallback: function() {
                    // Solo re-inicializamos los iconos tras paginación
                    if (window.lucide) lucide.createIcons();
                }
            });

            // --- BÚSQUEDA CUSTOM ---
            $('#customSearch').on('keyup', function() {
                tabla.search(this.value).draw();
            });

            // --- FILTRO DE FECHAS ---
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const fechaDesde = $('#fechaDesde').val();
                const fechaHasta = $('#fechaHasta').val();
                const rowNode = tabla.row(dataIndex).node();
                const fechaTexto = $(rowNode).find('td').eq(0).data('fecha'); // Columna 0 = Fecha

                if (!fechaTexto) return true;

                const fechaCirugia = new Date(fechaTexto);
                const desde = fechaDesde ? new Date(fechaDesde) : null;
                const hasta = fechaHasta ? new Date(fechaHasta) : null;

                // Ajuste de zona horaria simple
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

        // --- FUNCIONES DE EXPORTACIÓN ---
        function imprimirTablaCompleta() {
            const tablaOriginal = document.querySelector('#miTabla');
            const encabezado = tablaOriginal.querySelector('thead').outerHTML;
            const cuerpo = tablaOriginal.querySelector('tbody').outerHTML;

            const ventana = window.open('', '', 'width=900,height=700');
            ventana.document.write(`
                <html>
                <head>
                    <title>Libro de Cirugías</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; font-size: 12px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f8f9fa; font-weight: bold; }
                        h2 { color: #1B7D8F; }
                        .no-print { display: none; }
                    </style>
                </head>
                <body>
                    <h2>Reporte de Cirugías</h2>
                    <table>
                        ${encabezado}
                        ${cuerpo}
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

        async function exportarFiltradoPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF({
                orientation: 'landscape',
                format: 'legal'
            });

            const tablaDT = $('#miTabla').DataTable();
            const datosFiltrados = tablaDT.rows({
                search: 'applied'
            }).data();

            const headers = [
                ['Fecha', 'Hora', 'N°Q', 'Paciente', 'DNI', 'Procedimiento', 'Cirujano', 'Anestesiologo',
                    'Anestesia'
                ]
            ];
            const body = [];

            datosFiltrados.each(function(value, index) {
                const clean = (html) => {
                    const tmp = document.createElement("DIV");
                    tmp.innerHTML = html;
                    return tmp.textContent || tmp.innerText || "";
                }

                body.push([
                    clean(value[0]), // Fecha
                    clean(value[1]), // Hora
                    clean(value[2]), // N°Q
                    clean(value[5]), // Paciente
                    clean(value[4]), // DNI
                    clean(value[6]), // Procedimiento
                    clean(value[7]), // Cirujano
                    clean(value[10]), // Anestesiologo
                    clean(value[13]) // Anestesia
                ]);
            });

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
            doc.save('cirugias_filtradas.pdf');
        }

        document.getElementById('btnExportarExcel').addEventListener('click', function() {
            const tablaDT = $('#miTabla').DataTable();
            const datos = tablaDT.rows({
                search: 'applied'
            }).data().toArray();

            const clean = (html) => {
                const tmp = document.createElement("DIV");
                tmp.innerHTML = html;
                return tmp.textContent || tmp.innerText || "";
            }

            const dataExport = datos.map(row => ({
                Fecha: clean(row[0]),
                Hora: clean(row[1]),
                Quirofano: clean(row[2]),
                Edad: clean(row[3]),
                DNI: clean(row[4]),
                Paciente: clean(row[5]),
                Procedimiento: clean(row[6]),
                Cirujano: clean(row[7]),
                Anestesiologo: clean(row[10]),
                Instrumentador: clean(row[11]),
                Enfermero: clean(row[12]),
                Anestesia: clean(row[13]),
                Urgencia: clean(row[14]),
                Obito: clean(row[15])
            }));

            const ws = XLSX.utils.json_to_sheet(dataExport);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Cirugias");
            XLSX.writeFile(wb, "reporte_cirugias.xlsx");
        });
    </script>
@endpush
