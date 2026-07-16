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

        <!-- Botones de Acción -->
        <div class="mt-6 flex flex-wrap gap-3 justify-start">
            <button onclick="imprimirTablaCompleta()" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 hover:text-[#1B7D8F] transition-all shadow-sm">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span>Imprimir Tabla</span>
            </button>
            <button onclick="exportarFiltradoPDF()" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-red-600 rounded-xl hover:bg-red-50 transition-all shadow-sm">
                <i data-lucide="file-text" class="w-4 h-4"></i>
                <span>Exportar PDF</span>
            </button>
            <button id="btnExportarExcel" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-green-600 rounded-xl hover:bg-green-50 transition-all shadow-sm">
                <i data-lucide="sheet" class="w-4 h-4"></i>
                <span>Exportar Excel</span>
            </button>
        </div>

    </div>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .card, .shadow-sm { box-shadow: none !important; border: none !important; }
            /* Ocultar sidebar/nav si existe en el layout */
            aside, nav { display: none !important; }
            main { margin: 0 !important; padding: 0 !important; }
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
            background: #32989D !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 0.5rem !important;
        }

        /* Botones normales */
        #miTabla_wrapper .dataTables_paginate .paginate_button {
            background: #f9fafb !important;
            color: #374151 !important;
            border: none !important;
            border-radius: 0.5rem !important;
        }

        /* Hover en botones normales */
        #miTabla_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e5e7eb !important;
            color: #111827 !important;
            border: none !important;
            border-radius: 0.5rem !important;
        }

        /* Estado activo */
        #miTabla_wrapper .dataTables_paginate .paginate_button:active {
            background: #25636d !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 0.5rem !important;
        }
    </style>
@endsection

@push('scripts')
    <!-- jsPDF y autoTable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- SheetJS para generar archivos Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

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

            // --- BÚSQUEDA CUSTOM ---
            $('#customSearch').on('keyup', function() {
                tabla.search(this.value).draw();
            });

            // --- FILTRO DE FECHAS ---
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const fechaDesde = $('#fechaDesde').val();
                const fechaHasta = $('#fechaHasta').val();
                const rowNode = tabla.row(dataIndex).node();
                const fechaTexto = $(rowNode).find('td').eq(0).data('fecha');

                if (!fechaTexto) return true;

                const fechaEstudio = new Date(fechaTexto);
                const desde = fechaDesde ? new Date(fechaDesde) : null;
                const hasta = fechaHasta ? new Date(fechaHasta) : null;

                if(desde) desde.setHours(0,0,0,0);
                if(hasta) hasta.setHours(23,59,59,999);
                if(fechaEstudio) fechaEstudio.setHours(12,0,0,0);

                return (!desde || fechaEstudio >= desde) && (!hasta || fechaEstudio <= hasta);
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
                    <title>Estudios Médicos</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; font-size: 11px; }
                        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
                        th { background-color: #1B7D8F; color: white; font-weight: bold; }
                        .no-print { display: none; }
                    </style>
                </head>
                <body>
                    <h2>Reporte de Estudios Médicos</h2>
                    <table>
                        ${encabezado}
                        ${cuerpo}
                    </table>
                </body>
                </html>
            `);
            ventana.document.close();
            ventana.focus();
            setTimeout(() => { ventana.print(); ventana.close(); }, 500);
        }

        async function exportarFiltradoPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape', format: 'legal' });

            const tablaDT = $('#miTabla').DataTable();
            const datosFiltrados = tablaDT.rows({ search: 'applied' }).data();

            const headers = [['Fecha', 'Apellido y Nombre', 'I-A', 'DNI', 'Estudio Real', 'Regiones', 'Cont 50ml', 'Cont 100ml', 'Jer. Prel.', 'Descartables', 'Otros y Agujas']];
            const body = [];

            datosFiltrados.each(function(value, index) {
                const clean = (html) => {
                    const tmp = document.createElement("DIV");
                    tmp.innerHTML = html;
                    return tmp.textContent || tmp.innerText || "";
                }

                body.push([
                    clean(value[0]), // Fecha
                    clean(value[1]), // Apellido y Nombre
                    clean(value[2]), // I-A
                    clean(value[3]), // DNI
                    clean(value[4]), // Estudio Real
                    clean(value[5]), // Regiones
                    clean(value[6]), // Cont 50ml
                    clean(value[7]), // Cont 100ml
                    clean(value[8]), // Jer. Prel.
                    clean(value[9]), // Descartables
                    clean(value[10]) // Otros y Agujas
                ]);
            });

            doc.text("Reporte de Estudios Médicos", 14, 20);
            doc.autoTable({
                head: headers,
                body: body,
                startY: 30,
                theme: 'grid',
                styles: { fontSize: 8, cellPadding: 2 },
                headStyles: { fillColor: [27, 125, 143] }
            });
            doc.save('estudios_medicos_filtrados.pdf');
        }

        document.getElementById('btnExportarExcel').addEventListener('click', function() {
            const tablaDT = $('#miTabla').DataTable();
            const datos = tablaDT.rows({ search: 'applied' }).data().toArray();

            const clean = (html) => {
                const tmp = document.createElement("DIV");
                tmp.innerHTML = html;
                return tmp.textContent || tmp.innerText || "";
            }

            const dataExport = datos.map(row => ({
                Fecha: clean(row[0]),
                Apellido_y_Nombre: clean(row[1]),
                IA: clean(row[2]),
                DNI: clean(row[3]),
                Estudio_Real: clean(row[4]),
                Regiones: clean(row[5]),
                Contraste_50ml: clean(row[6]),
                Contraste_100ml: clean(row[7]),
                Jeringa_Prellenada: clean(row[8]),
                Descartables: clean(row[9]),
                Otros_y_Agujas: clean(row[10])
            }));

            const ws = XLSX.utils.json_to_sheet(dataExport);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Estudios Medicos");
            XLSX.writeFile(wb, "reporte_estudios_medicos.xlsx");
        });
    </script>
@endpush