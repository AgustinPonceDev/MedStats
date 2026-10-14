@extends('layouts.app')
@section('titulo', 'Medicamentos en Stock')
@section('contenido')

    @if (session('success'))
        <div class="mb-6 px-4 py-3 bg-green-100 text-green-800 rounded shadow-sm border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex min-h-screen transition-all duration-300 ease-in-out">
        <main class="flex-1 p-5 max-w-full">
            <div class="flex justify-between items-center mb-4">
                <h1
                    class="text-2xl font-bold bg-gradient-to-r from-[#1B7D8F] via-[#2BA8A0] to-[#245360] text-transparent  bg-clip-text drop-shadow-md  flex items-center gap-2 px-2">
                    Medicamentos en Stock</h1>

                <div class="flex gap-4 items-center">
                    <form method="GET" action="{{ route('stocks.index') }}" class="flex items-center gap-3">
                        <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-lg"
                            style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 2px solid #1B7D8F;">
                            <label for="servicio_id" class="font-semibold mb-0" style="color: #1B7D8F;">Filtrar por
                                Servicio:</label>
                            <select name="servicio_id" id="servicio_id" class="form-select"
                                style="border: 2px solid #1B7D8F; border-radius: 8px; min-width: 200px; font-weight: 500;"
                                onchange="this.form.submit()" {{ $servicioRestringido ? 'disabled' : '' }}>
                                @if (!$servicioRestringido)
                                    <option value="">Todos los Servicios</option>
                                @endif
                                @foreach ($servicios as $s)
                                    <option value="{{ $s->id }}"
                                        {{ request('servicio_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($servicioRestringido)
                                <span class="badge"
                                    style="background-color: #1B7D8F; font-size: 0.75rem;">Restringido</span>
                            @endif
                        </div>
                    </form>

                    <a href="{{ route('stocks.create') }}"
                        class="inline-block bg-neutral-700 hover:bg-neutral-800 text-white font-medium py-2 px-6 rounded-full shadow-md cursor-pointer transition duration-300"
                        style="text-decoration: none;">
                        Ingresar Nuevo Medicamento
                    </a>
                </div>
            </div>

            {{-- Filtros y Controles --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
                <div class="flex flex-col lg:flex-row gap-5 justify-between items-end lg:items-center">

                    {{-- Escaneo rápido (Ocupa la mitad de la card: lg:w-1/2) --}}
                    <div class="w-full lg:w-1/2">
                        <label for="scan_rapido"
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Escaneo
                            rápido</label>
                        <div class="relative flex items-center">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#1B7D8F]">
                                <i data-lucide="scan-line" class="w-4 h-4"></i>
                            </div>
                            <input type="text" id="scan_rapido" autocomplete="off"
                                class="pl-10 pr-10 py-2 bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#1B7D8F] focus:border-[#1B7D8F] block w-full transition-colors"
                                placeholder="Escaneá el código de barras...">

                            <!-- Botón de borrado rápido (X) -->
                            <button type="button" id="btn_limpiar_scan"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none hidden"
                                title="Limpiar búsqueda">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <span id="scan_rapido_msg" class="text-xs text-gray-400 mt-1 block"></span>
                    </div>

                    {{-- Buscador Global (Queda exactamente igual, con su tamaño original lg:w-72) --}}
                    <div class="w-full lg:w-72">
                        <label for="customSearch"
                            class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Buscar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </div>
                            <input type="text" id="customSearch" placeholder="Medicamentos, Servicio..."
                                class="pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg focus:ring-[#1B7D8F] focus:border-[#1B7D8F] block w-full transition-colors">
                        </div>
                    </div>
                </div>
            </div>


            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto rounded-t-lg">
                    <table id="tablaStock" class="w-full text-xs text-left text-gray-500" style="width:100%">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border">Medicamento</th>
                                <th class="px-4 py-2 border">Servicio</th>
                                <th class="px-4 py-2 border">Lote</th>
                                <th class="px-4 py-2 border">Fecha de vencimiento</th>
                                <th class="px-4 py-2 border text-center">Cantidad actual</th>
                                <th class="px-4 py-2 border text-center">Proyección</th>
                                <th class="px-4 py-2 border text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stock as $item)
                                @php
                                    $estado = $item->estadoStock();
                                    $claseColor = match ($estado) {
                                        'critico' => 'text-red-600 font-bold',
                                        'aviso' => 'text-yellow-600 font-medium',
                                        default => 'text-green-700 font-medium',
                                    };

                                    $proy = $item->proyeccion ?? null;
                                    $urgenciaProy = $proy['urgencia'] ?? 'sin_datos';
                                    [$badgeClase, $badgeTexto] = match ($urgenciaProy) {
                                        'critico' => [
                                            'bg-red-100 text-red-700 border border-red-200',
                                            'Se agota en ' . $proy['dias_restantes'] . ' días',
                                        ],
                                        'aviso' => [
                                            'bg-yellow-100 text-yellow-700 border border-yellow-200',
                                            'Se agota en ' . $proy['dias_restantes'] . ' días',
                                        ],
                                        'ok' => [
                                            'bg-green-100 text-green-700 border border-green-200',
                                            $proy['dias_restantes']
                                                ? 'Alcanza ' . $proy['dias_restantes'] . ' días'
                                                : 'Sin agotamiento próximo',
                                        ],
                                        default => [
                                            'bg-gray-100 text-gray-500 border border-gray-200',
                                            'Sin consumo reciente',
                                        ],
                                    };
                                @endphp

                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border">{{ $item->get_medicamento->nombre }}</td>
                                    <td class="px-4 py-2 border">{{ $item->get_servicio->nombre }}</td>
                                    <td class="px-4 py-2 border">{{ $item->lote }}</td>
                                    <td class="px-4 py-2 border">{{ $item->fecha_vencimiento }}</td>
                                    <td class="px-4 py-2 border text-center">
                                        <span class="{{ $claseColor }}"
                                            title="Aviso: {{ $item->umbral_aviso ?? 50 }} · Crítico: {{ $item->umbral_critico ?? 30 }}">
                                            {{ $item->cantidad_act }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border text-center">
                                        <span
                                            class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $badgeClase }}"
                                            title="Promedio ponderado: {{ $proy['consumo_diario_ponderado'] ?? 0 }}/día · Tendencia: {{ $proy['tendencia'] ?? '-' }}">
                                            {{ $badgeTexto }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-center whitespace-nowrap border">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Historial / Ver Detalle -->
                                            <a href="{{ route('stocks.show', $item) }}"
                                                class="p-1 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors"
                                                title="Historial">
                                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                            </a>

                                            <!-- Editar -->
                                            <a href="{{ route('stocks.edit', $item->id) }}"
                                                class="p-1 bg-amber-50 text-amber-600 rounded hover:bg-amber-100 transition-colors"
                                                title="Editar">
                                                <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                            </a>

                                            <!-- Agregar Stock -->
                                            <a href="{{ route('stocks.edit', ['stock' => $item->id, 'modo' => 'agregar']) }}"
                                                class="p-1 bg-emerald-50 text-emerald-600 rounded hover:bg-emerald-100 transition-colors"
                                                title="Agregar Stock">
                                                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                                            </a>

                                            <!-- Extraer Stock -->
                                            <a href="{{ route('stocks.edit', ['stock' => $item->id, 'modo' => 'extraer']) }}"
                                                class="p-1 bg-rose-50 text-rose-600 rounded hover:bg-rose-100 transition-colors"
                                                title="Extraer Stock">
                                                <i data-lucide="minus-circle" class="w-3.5 h-3.5"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-2 text-center text-gray-500">No hay medicamentos en
                                        stock.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar íconos de Lucide si existen
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

            const tabla = $('#tablaStock').DataTable({
                dom: 'rt<"flex items-center justify-between px-6 py-3"ip>',
                language: idiomaEspanol,
                pageLength: 10, // Puedes ajustarlo a los registros que prefieras por página
                order: [
                    [2, 'asc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [4, 5, 6]
                }],
                drawCallback: function() {
                    if (window.lucide) {
                        lucide.createIcons();
                    }
                }
            });

            // --- BÚSQUEDA CUSTOM ---
            $('#customSearch').on('keyup', function() {
                tabla.search(this.value).draw();
            });

            // ----------------------------------------------------
            // Lógica de Escaneo Rápido (con persistencia y botón X)
            // ----------------------------------------------------
            const inputScan = document.getElementById('scan_rapido');
            const btnLimpiar = document.getElementById('btn_limpiar_scan');
            const msg = document.getElementById('scan_rapido_msg');

            if (inputScan) {
                inputScan.focus();
            }

            function actualizarVisibilidadBotonX() {
                if (inputScan && btnLimpiar) {
                    if (inputScan.value.trim().length > 0) {
                        btnLimpiar.classList.remove('hidden');
                    } else {
                        btnLimpiar.classList.add('hidden');
                    }
                }
            }

            if (inputScan) {
                inputScan.addEventListener('keydown', function(e) {
                    if (e.key !== 'Enter') return;
                    e.preventDefault();
                    const barcode = inputScan.value.trim();

                    actualizarVisibilidadBotonX();

                    if (!barcode) return;

                    if (msg) msg.textContent = 'Buscando...';

                    fetch(`{{ route('stocks.buscarPorBarcode') }}?barcode=${encodeURIComponent(barcode)}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.tipo === 'lote_existente') {
                                if (msg) {
                                    msg.innerHTML =
                                        `<strong>${data.stock.medicamento}</strong> (lote ${data.stock.lote}, ${data.stock.cantidad_act} u.) — ` +
                                        `<a href="${data.stock.url_agregar}" class="text-green-600 underline font-semibold">Agregar</a> ` +
                                        `&nbsp;|&nbsp; <a href="${data.stock.url_extraer}" class="text-red-600 underline font-semibold">Extraer</a>`;
                                }
                                // Filtrar tabla con el lote o nombre exacto
                                tabla.search(data.stock.lote).draw();

                            } else if (data.tipo === 'producto_conocido') {
                                if (msg) {
                                    msg.textContent =
                                        `Catálogo: ${data.producto.medicamento_nombre} (No hay stock activo para este lote exacto).`;
                                }
                                tabla.search(data.producto.medicamento_nombre).draw();
                            } else {
                                if (msg) {
                                    msg.textContent = 'Código no encontrado en ningún lote cargado.';
                                }
                                tabla.search(barcode).draw();
                            }
                        })
                        .catch(() => {
                            if (msg) msg.textContent = 'Error al buscar el código.';
                        });
                });

                inputScan.addEventListener('input', function() {
                    actualizarVisibilidadBotonX();
                });
            }

            if (btnLimpiar) {
                btnLimpiar.addEventListener('click', function() {
                    inputScan.value = '';
                    if (msg) msg.textContent = '';
                    actualizarVisibilidadBotonX();
                    tabla.search('').draw(); // Restablecer la tabla de DataTables
                    inputScan.focus();
                });
            }
        });
    </script>

    <style>
        /* Solución definitiva para los píxeles blancos en las esquinas redondeadas */
        .overflow-hidden {
            overflow: hidden;
            -webkit-mask-image: -webkit-radial-gradient(white, black);
            /* Forzar renderizado limpio en Safari/Chrome */
        }

        #tablaStock {
            border-radius: 0.5rem 0.5rem 0 0;
            overflow: hidden;
        }

        #tablaStock thead th:first-child {
            border-top-left-radius: 0.5rem;
        }

        #tablaStock thead th:last-child {
            border-top-right-radius: 0.5rem;
        }

        /* Estilos personalizados heredados y adaptados para la tabla de Stock */
        #tablaStock_wrapper {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #tablaStock {
            width: 100% !important;
            margin: 0 !important;
        }

        #tablaStock_wrapper table.dataTable.no-footer {
            border-top: none !important;
            border-bottom: none !important;
        }

        #tablaStock_wrapper .dataTables_length,
        #tablaStock_wrapper .dataTables_filter {
            display: none !important;
        }

        #tablaStock_wrapper .dataTables_info {
            color: #6b7280 !important;
            font-size: 0.875rem !important;
            padding-top: 0 !important;
        }

        #tablaStock_wrapper .dataTables_paginate .paginate_button.current,
        #tablaStock_wrapper .dataTables_paginate .paginate_button.current:hover,
        #tablaStock_wrapper .dataTables_paginate .paginate_button.current:active {
            background: #32989D !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 0.5rem !important;
        }

        #tablaStock_wrapper .dataTables_paginate .paginate_button {
            background: #f9fafb !important;
            color: #374151 !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.35rem 0.75rem !important;
            margin-left: 0.2rem !important;
            margin-right: 0.2rem !important;
            cursor: pointer;
        }

        #tablaStock_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e5e7eb !important;
            color: #111827 !important;
            border: none !important;
            border-radius: 0.5rem !important;
        }

        #tablaStock_wrapper .dataTables_paginate .paginate_button:active {
            background: #25636d !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 0.5rem !important;
        }

        #tablaStock_wrapper .dataTables_paginate .paginate_button.disabled,
        #tablaStock_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            background: #f9fafb !important;
            color: #9ca3af !important;
            border: none !important;
            cursor: default !important;
        }

        div.dt-buttons {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        div.dt-buttons .btn {
            border-radius: 0.5rem;
            margin-right: 0.5rem;
        }

        @media (max-width: 768px) {
            #tablaStock_wrapper .dataTables_info {
                font-size: 0.75rem !important;
            }

            #tablaStock_wrapper .dataTables_paginate .paginate_button {
                padding: 0.3rem 0.55rem !important;
                margin-left: 0.1rem !important;
                margin-right: 0.1rem !important;
            }
        }
    </style>
@endpush