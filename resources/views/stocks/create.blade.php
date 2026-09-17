@extends('layouts.app')
@section('titulo', 'Ingresar Medicamento')
@section('contenido')
<div class="max-w-3xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1
                class="text-2xl font-bold bg-gradient-to-r from-[#1B7D8F] via-[#2BA8A0] to-[#245360] text-transparent  bg-clip-text drop-shadow-md  flex items-center gap-2 px-2">
                Ingresar nuevo medicamento al stock</h1>
        </div>

        <!-- Escaneo de código de barras -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-4 mb-4">
            <label for="scan_input" class="block text-sm font-medium text-gray-700 mb-1">
                Escanear código de barras (opcional)
            </label>
            <div class="flex items-center gap-2">
                <input type="text" id="scan_input" autocomplete="off"
                    class="flex-1 border-2 border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-[#1B7D8F]"
                    placeholder="Apuntá el lector acá y escaneá, o escribilo a mano...">
            </div>
            <p id="scan_msg" class="text-xs text-gray-500 mt-2"></p>
        </div>

        <form action="{{ route('stocks.store') }}" method="POST"
            class="bg-white shadow rounded-lg border border-gray-200 p-6 space-y-6">
            @csrf

            <input type="hidden" name="barcode" id="barcode_hidden" value="{{ old('barcode') }}">

            <!-- Medicamento con autocompletado -->
            <div>
                <label for="medicamento_input" class="block text-sm font-medium text-gray-700 mb-1">Medicamento</label>
                <input type="text" id="medicamento_input" name="medicamento_id"
                    class="w-full border-2 border-gray-400 rounded-md shadow-sm px-4 py-2 focus:ring-2 focus:ring-gray-200 focus:border-gray-700"
                    placeholder="Escriba para buscar un medicamento...">
                @error('medicamento_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Detalles del lote -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="cantidad_act" class="block text-sm font-medium text-gray-700 mb-1">Cantidad</label>
                    <input type="number" name="cantidad_act" id="cantidad_act"
                        class="w-full border-2 border-gray-400 rounded-md shadow-sm px-4 py-2 focus:ring-2 focus:ring-gray-500 focus:border-gray-700"
                        value="{{ old('cantidad_act') }}">
                    @error('cantidad_act')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="lote" class="block text-sm font-medium text-gray-700 mb-1">Lote</label>
                    <input type="text" name="lote" id="lote"
                        class="w-full border-2 border-gray-400 rounded-md shadow-sm px-4 py-2 focus:ring-2 focus:ring-gray-500 focus:border-gray-700"
                        value="{{ old('lote') }}">
                    @error('lote')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div>
                    <label for="servicio_id" class="block text-sm font-medium text-gray-700 mb-1">Servicio</label>
                    <select name="servicio_id" id="servicio_id"
                        class="w-full border-2 border-gray-400 rounded-md shadow-sm px-4 py-2 focus:ring-2 focus:ring-gray-500 focus:border-gray-700">
                        @foreach ($servicios as $servicio)
                            <option value="{{ $servicio->id }}" {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                                {{ $servicio->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('servicio_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div>
                <label for="fecha_vencimiento" class="block text-sm font-medium text-gray-700 mb-1">Fecha de
                    vencimiento</label>
                <input type="date" name="fecha_vencimiento" id="fecha_vencimiento"
                    class="w-full border-2 border-gray-400 rounded-md shadow-sm px-4 py-2 focus:ring-2 focus:ring-gray-500 focus:border-gray-700"
                    value="{{ old('fecha_vencimiento') }}">
                @error('fecha_vencimiento')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Guardar en catálogo de códigos -->
            <div class="flex items-center gap-2" id="guardar_catalogo_wrapper" style="display:none;">
                <input type="checkbox" name="guardar_en_catalogo" id="guardar_en_catalogo" value="1" checked
                    class="rounded border-gray-400 text-[#1B7D8F] focus:ring-[#1B7D8F]">
                <label for="guardar_en_catalogo" class="text-sm text-gray-700">
                    Guardar este código en el catálogo, para autocompletar el medicamento la próxima vez que llegue
                </label>
            </div>

            <!-- Umbrales de aviso / crítico -->
            <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-1">Umbrales de Stock Bajo</h3>
                <p class="text-xs text-gray-500 mb-4">
                    Definen cuándo este insumo se muestra en amarillo (aviso) o en rojo (crítico) en el listado.
                    Si los dejás vacíos, se usan 50 y 30 por defecto.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="umbral_aviso" class="block text-sm font-medium text-gray-700 mb-1">Umbral de Aviso (🟡)</label>
                        <input type="number" name="umbral_aviso" id="umbral_aviso" min="0"
                            class="w-full border-2 border-gray-400 rounded-md shadow-sm px-4 py-2 focus:ring-2 focus:ring-yellow-400 focus:border-yellow-500"
                            placeholder="Ej: 50" value="{{ old('umbral_aviso') }}">
                        @error('umbral_aviso')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div>
                        <label for="umbral_critico" class="block text-sm font-medium text-gray-700 mb-1">Umbral Crítico (🔴)</label>
                        <input type="number" name="umbral_critico" id="umbral_critico" min="0"
                            class="w-full border-2 border-gray-400 rounded-md shadow-sm px-4 py-2 focus:ring-2 focus:ring-red-400 focus:border-red-500"
                            placeholder="Ej: 30" value="{{ old('umbral_critico') }}">
                        @error('umbral_critico')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Botón -->
            <div class="flex justify-between pt-4">
                <a href="{{ route('stocks.index') }}" class="btn btn-outline-danger px-5 py-2 rounded shadow-sm">
                    ← Cancelar
                </a>
                <button type="submit"
                    class="inline-block bg-neutral-700 hover:bg-neutral-800 text-white font-medium py-2 px-6 rounded-full shadow-md cursor-pointer transition duration-300"
                    style="text-decoration: none;">
                    Agregar
                </button>
            </div>
        </form>

    <!-- Script para alerta de vencimiento -->
        <script>
            const vencimiento = document.getElementById('fecha_vencimiento');
            vencimiento.addEventListener('change', () => {
                const inputDate = new Date(vencimiento.value);
                const today = new Date();
                const limit = new Date();
                limit.setDate(today.getDate() + 15);

                if (inputDate <= today) {
                    showWarning("Este medicamento está vencido o vence hoy.");
                } else if (inputDate <= limit) {
                    showWarning("Este medicamento vence en los próximos 15 días.");
                } else {
                    hideWarning();
                }
            });

            function showWarning(message) {
                let alertBox = document.getElementById('vencimiento-alert');
                if (!alertBox) {
                    alertBox = document.createElement('div');
                    alertBox.id = 'vencimiento-alert';
                    alertBox.className =
                        'mb-4 px-4 py-3 bg-yellow-100 border border-yellow-300 text-yellow-800 rounded shadow-sm';
                    vencimiento.parentNode.insertBefore(alertBox, vencimiento.parentNode.firstChild);
                }
                alertBox.innerText = message;
            }

            function hideWarning() {
                const alertBox = document.getElementById('vencimiento-alert');
                if (alertBox) alertBox.remove();
            }
        </script>

        <!-- Tom Select CSS y JS -->
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

        <!-- Inicialización de autocompletado -->
        <script>
            const medicamentosOptions = [
                @foreach ($medicamentos as $id => $nombre)
                    { value: '{{ $id }}', text: '{{ $nombre }}' },
                @endforeach
            ];

            const tomSelectMedicamento = new TomSelect('#medicamento_input', {
                options: medicamentosOptions,
                create: false,
                maxItems: 1,
                placeholder: "Escriba para buscar un medicamento...",
                allowEmptyOption: true,
                sortField: { field: "text", direction: "asc" },
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results">No se encontró ningún medicamento</div>';
                    }
                }
            });

            // ---------- Escaneo de código de barras ----------
            const scanInput = document.getElementById('scan_input');
            const scanMsg = document.getElementById('scan_msg');
            const barcodeHidden = document.getElementById('barcode_hidden');
            const guardarCatalogoWrapper = document.getElementById('guardar_catalogo_wrapper');
            const guardarCatalogoCheckbox = document.getElementById('guardar_en_catalogo');
            scanInput.focus();

            scanInput.addEventListener('keydown', function(e) {
                if (e.key !== 'Enter') return;
                e.preventDefault();
                const barcode = scanInput.value.trim();
                if (!barcode) return;

                scanMsg.textContent = 'Buscando...';
                fetch(`{{ route('stocks.buscarPorBarcode') }}?barcode=${encodeURIComponent(barcode)}`)
                    .then(res => res.json())
                    .then(data => {
                        barcodeHidden.value = barcode;

                        if (data.tipo === 'lote_existente') {
                            scanMsg.innerHTML = `Este código ya pertenece a un lote cargado (${data.stock.medicamento}, lote ${data.stock.lote}, ${data.stock.cantidad_act} u.). ` +
                                `<a href="${data.stock.url_agregar}" class="text-green-600 underline font-semibold">Agregar stock →</a>` +
                                ` &nbsp;|&nbsp; <a href="${data.stock.url_extraer}" class="text-red-600 underline font-semibold">Extraer stock →</a>`;
                            guardarCatalogoWrapper.style.display = 'none';
                        } else if (data.tipo === 'producto_conocido') {
                            tomSelectMedicamento.setValue(String(data.producto.medicamento_id));
                            if (data.producto.lote_sugerido) document.getElementById('lote').value = data.producto.lote_sugerido;
                            if (data.producto.fecha_vencimiento_sugerida) document.getElementById('fecha_vencimiento').value = data.producto.fecha_vencimiento_sugerida;
                            scanMsg.textContent = `Código conocido: ${data.producto.medicamento_nombre}. Revisá lote, vencimiento y cantidad (son solo una sugerencia del último ingreso).`;
                            guardarCatalogoWrapper.style.display = 'flex';
                            guardarCatalogoCheckbox.checked = false;
                            document.getElementById('cantidad_act').focus();
                        } else {
                            scanMsg.textContent = 'Código nuevo — completá los datos manualmente. Se puede guardar en el catálogo para la próxima vez.';
                            guardarCatalogoWrapper.style.display = 'flex';
                            guardarCatalogoCheckbox.checked = true;
                            document.getElementById('medicamento_input').focus();
                        }
                    })
                    .catch(() => {
                        scanMsg.textContent = 'Error al buscar el código, podés seguir cargando a mano.';
                    });

                scanInput.value = '';
            });
        </script>
</div>
@endsection
