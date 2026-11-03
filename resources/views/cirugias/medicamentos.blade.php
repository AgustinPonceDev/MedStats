@extends('layouts.app')

@section('titulo', 'Cargar Medicamentos - Cirugía #' . $cirugia->id)

@section('contenido')
    <div class="max-w-[1600px] mx-auto px-4 py-8">
        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 mb-8 transition duration-300 hover:shadow-2xl">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <span
                        class="bg-[#e6f4f3] text-[#1B7D8F] text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">
                        Cirugía #{{ $cirugia->id }}
                    </span>
                    <h1 class="text-3xl font-extrabold text-gray-800 mt-2">
                        Cargar Medicamentos e Insumos
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Gestione los consumibles descontados del stock de Quirófano para esta cirugía.
                    </p>
                </div>
                <div>
                    <a href="{{ route('cirugias.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-gray-900 transition">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i> Volver a Cirugías
                    </a>
                </div>
            </div>

            <hr class="my-6 border-gray-100">

            <!-- Surgery Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div class="flex items-start gap-3">
                    <div class="p-3 bg-[#e6f4f3] text-[#1B7D8F] rounded-xl">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block uppercase font-medium">Paciente</span>
                        <span class="text-sm font-bold text-gray-700">
                            {{ $cirugia->get_paciente->nombre }} {{ $cirugia->get_paciente->apellido }}
                        </span>
                        <span class="text-xs text-gray-500 block">DNI: {{ $cirugia->get_paciente->dni }}</span>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="p-3 bg-[#e6f4f3] text-[#1B7D8F] rounded-xl">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block uppercase font-medium">Fecha y Hora</span>
                        <span class="text-sm font-bold text-gray-700">
                            {{ \Carbon\Carbon::parse($cirugia->fecha_cirugia)->format('d/m/Y') }}
                        </span>
                        <span class="text-xs text-gray-500 block">{{ $cirugia->hora_cirugia }} hs</span>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="p-3 bg-[#e6f4f3] text-[#1B7D8F] rounded-xl">
                        <i data-lucide="activity" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block uppercase font-medium">Cirujano</span>
                        <span class="text-sm font-bold text-gray-700">
                            Dr/a. {{ $cirugia->get_cirujano->nombre }} {{ $cirugia->get_cirujano->apellido }}
                        </span>
                        <span class="text-xs text-gray-500 block">Mat:
                            {{ $cirugia->get_cirujano->matricula ?? 'S/M' }}</span>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="p-3 bg-[#e6f4f3] text-[#1B7D8F] rounded-xl">
                        <i data-lucide="home" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 block uppercase font-medium">Quirófano</span>
                        <span class="text-sm font-bold text-gray-700">
                            {{ $cirugia->get_quirofano->nombre }}
                        </span>
                        <span
                            class="text-xs text-gray-500 block">{{ $cirugia->get_quirofano->descripcion ?? 'Sin descripción' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div
                class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl mb-6 shadow-sm flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500 flex-shrink-0"></i>
                <span class="text-sm font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-800 p-4 rounded-xl mb-6 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500 flex-shrink-0"></i>
                    <span class="text-sm font-bold">Por favor corrija los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 pl-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Two Column Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left Column: Add Medication Form -->
            <div class="lg:col-span-4 xl:col-span-3">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-24 space-y-6">

                    {{-- Escaneo con lector de código de barras --}}
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 mb-1 flex items-center gap-2">
                            <i data-lucide="scan-barcode" class="w-5 h-5 text-[#1B7D8F]"></i> Escanear código
                        </h2>
                        <p class="text-xs text-gray-400 mb-3">Cada lectura descuenta del stock de Quirófano.</p>

                        <form action="{{ route('cirugias.medicamentos.escanear', $cirugia) }}" method="POST"
                            class="space-y-3" id="formEscaneo">
                            @csrf
                            <div>
                                <label for="barcode_scan"
                                    class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                    Código de barras
                                </label>
                                <input type="text" name="barcode" id="barcode_scan" autocomplete="off" required
                                    placeholder="Enfocá acá y escaneá..."
                                    class="w-full rounded-lg border border-gray-200 shadow-sm px-4 py-2 focus:ring-2 focus:ring-[#1B7D8F] focus:border-transparent text-sm font-mono">
                            </div>
                            <div>
                                <label for="unidades_scan"
                                    class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                    Unidades por lectura
                                </label>
                                <input type="number" name="unidades" id="unidades_scan" min="1" max="999"
                                    value="{{ old('unidades', 1) }}"
                                    class="w-full rounded-lg border border-gray-200 shadow-sm px-4 py-2 focus:ring-2 focus:ring-[#1B7D8F] focus:border-transparent text-sm">
                            </div>
                            <p class="text-xs text-gray-400">Escaneá el código, indicá las unidades y presioná Enter (o el
                                botón) para descontar.</p>
                            <button type="submit"
                                class="w-full border border-[#1B7D8F] text-[#1B7D8F] hover:bg-[#e6f4f3] font-semibold py-2 px-4 rounded-xl transition flex items-center justify-center gap-2 text-sm">
                                <i data-lucide="scan-barcode" class="w-4 h-4"></i> Descontar lectura
                            </button>
                        </form>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Carga manual (sin código de barras) --}}
                    <div>
                        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i data-lucide="plus" class="w-5 h-5 text-[#1B7D8F]"></i> Cargar Manual
                        </h2>

                        <form action="{{ route('cirugias.medicamentos.store', $cirugia) }}" method="POST"
                            class="space-y-4">
                            @csrf

                            <div>
                                <label for="stock_id"
                                    class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                    Medicamento / Insumo (Lote - Stock)
                                </label>
                                <select name="stock_id" id="stock_id" class="form-control select2 w-full" required>
                                    <option value="">Seleccione el insumo</option>
                                    @foreach ($stocks as $stock)
                                        <option value="{{ $stock->id }}"
                                            {{ old('stock_id') == $stock->id ? 'selected' : '' }}>
                                            {{ $stock->get_medicamento->nombre }} (Lote: {{ $stock->lote }} – Disp:
                                            {{ $stock->cantidad_act }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="cantidad"
                                    class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                    Cantidad a extraer
                                </label>
                                <input type="number" name="cantidad" id="cantidad" min="1"
                                    class="w-full rounded-lg border border-gray-200 shadow-sm px-4 py-2 focus:ring-2 focus:ring-[#1B7D8F] focus:border-transparent text-sm"
                                    value="{{ old('cantidad', 1) }}" required>
                            </div>

                            <button type="submit"
                                class="w-full bg-[#1B7D8F] hover:bg-[#15606e] text-white font-bold py-2.5 px-4 rounded-xl shadow-md transition flex items-center justify-center gap-2">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Descontar del Stock
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Loaded Medications List -->
            <div class="lg:col-span-8 xl:col-span-9">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i data-lucide="list-checks" class="w-5 h-5 text-[#1B7D8F]"></i> Medicamentos Cargados
                    </h2>

                    @if ($consumidos->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 text-center">
                            <div class="p-4 bg-gray-50 rounded-full text-gray-400 mb-4">
                                <i data-lucide="shopping-cart" class="w-8 h-8"></i>
                            </div>
                            <h3 class="font-semibold text-gray-600">No hay medicamentos cargados</h3>
                            <p class="text-xs text-gray-400 mt-1 max-w-sm">
                                Los medicamentos y descartables utilizados durante el acto quirúrgico se listarán aquí una
                                vez descontados del Quirófano.
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr
                                        class="border-b border-gray-100 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                        <th class="py-3 px-3">Medicamento / Insumo</th>
                                        <th class="py-3 px-3">Lote</th>
                                        <th class="py-3 px-3">Código</th>
                                        <th class="py-3 px-3">Vencimiento</th>
                                        <th class="py-3 px-3 text-center">Cantidad</th>
                                        <th class="py-3 px-3 text-right">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($consumidos as $item)
                                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                                            <td class="py-4 px-3">
                                                <span class="font-bold text-gray-700">
                                                    {{ $item->get_stock->get_medicamento->nombre ?? 'Insumo Eliminado' }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-3 text-sm text-gray-500">
                                                {{ $item->get_stock->lote ?? 'N/D' }}
                                            </td>
                                            <td class="py-4 px-3 text-xs text-gray-400 font-mono">
                                                {{ optional($item->get_stock)->barcode ?? '—' }}
                                            </td>
                                            <td class="py-4 px-3 text-sm text-gray-500">
                                                {{ $item->get_stock->fecha_vencimiento ? \Carbon\Carbon::parse($item->get_stock->fecha_vencimiento)->format('d/m/Y') : 'N/D' }}
                                            </td>
                                            <td class="py-4 px-3 text-center">
                                                <span
                                                    class="bg-rose-50 text-rose-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                                    {{ abs($item->cantidad) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-3 text-right whitespace-nowrap">
                                                <form
                                                    action="{{ route('cirugias.medicamentos.destroy', [$cirugia, $item]) }}"
                                                    method="POST" onsubmit="return confirmarDevolucion(this, event);"
                                                    data-insumo="{{ $item->get_stock->get_medicamento->nombre ?? 'Insumo' }}"
                                                    data-lote="{{ $item->get_stock->lote ?? 'N/D' }}"
                                                    data-cargadas="{{ abs($item->cantidad) }}"
                                                    class="inline-flex items-center justify-end gap-1.5">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="number" name="cantidad" min="1"
                                                        max="{{ abs($item->cantidad) }}" value="1"
                                                        class="w-16 rounded-lg border border-gray-200 px-2 py-1 text-xs text-center focus:ring-2 focus:ring-[#1B7D8F] focus:border-transparent"
                                                        title="Unidades a devolver al stock">
                                                    <button type="submit"
                                                        class="text-rose-500 hover:text-rose-700 p-1.5 hover:bg-rose-50 rounded-lg transition"
                                                        title="Devolver al stock las unidades indicadas">
                                                        <i data-lucide="undo-2" class="w-4 h-4"></i>
                                                    </button>
                                                    <button type="submit" name="todo" value="1"
                                                        class="text-xs font-semibold text-rose-600 hover:bg-rose-50 px-2 py-1.5 rounded-lg transition"
                                                        title="Quitar toda la carga y devolver todo al stock">
                                                        Todo
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <div id="modalDevolucion" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4"
        role="dialog" aria-modal="true" aria-labelledby="modalDevTitulo">
        <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 shrink-0 rounded-full bg-rose-50 flex items-center justify-center text-rose-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M9 14 4 9l5-5" />
                        <path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11" />
                    </svg>
                </div>
                <div>
                    <h3 id="modalDevTitulo" class="text-base font-bold text-gray-800">Devolver al stock</h3>
                    <p id="modalDevTexto" class="mt-1 text-sm text-gray-500 leading-relaxed"></p>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" id="modalDevCancel"
                    class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">Cancelar</button>
                <button type="button" id="modalDevOk"
                    class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-[#1B7D8F] hover:bg-[#25636d] transition">Sí,
                    devolver</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Confirmación propia para devolver insumos al stock    
            let formDevolucion = null;
            let devolverTodo = false;

            function confirmarDevolucion(form, ev) {
                const modal = document.getElementById('modalDevolucion');
                if (!modal) return true; // si falta el modal, cae al envío normal        

                devolverTodo = !!(ev.submitter && ev.submitter.name === 'todo');
                formDevolucion = form;

                const insumo = form.dataset.insumo || 'este insumo';
                const lote = form.dataset.lote || 'N/D';
                const cargadas = parseInt(form.dataset.cargadas, 10) || 0;
                const n = parseInt(form.querySelector('input[name="cantidad"]').value, 10);

                const titulo = document.getElementById('modalDevTitulo');
                const texto = document.getElementById('modalDevTexto');
                const btnOk = document.getElementById('modalDevOk');
                const btnCancel = document.getElementById('modalDevCancel');

                btnOk.classList.remove('hidden');
                btnCancel.textContent = 'Cancelar';

                if (!devolverTodo && (!n || n < 1 || n > cargadas)) {
                    // Cantidad inválida: avisamos y no dejamos confirmar            
                    titulo.textContent = 'Cantidad no válida';
                    texto.textContent = 'Indicá una cantidad entre 1 y ' + cargadas + ' para ' + insumo + '.';
                    btnOk.classList.add('hidden');
                    btnCancel.textContent = 'Entendido';
                } else if (devolverTodo) {
                    titulo.textContent = 'Quitar toda la carga';
                    texto.textContent = 'Se van a devolver ' + cargadas + ' unidad(es) de ' + insumo + ' (lote ' + lote +
                        ') al stock de Quirófano.';
                } else {
                    titulo.textContent = 'Devolver al stock';
                    texto.textContent = 'Vas a devolver ' + n + ' unidad(es) de ' + insumo + ' (lote ' + lote +
                        ') al stock de Quirófano.';
                }

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                (btnOk.classList.contains('hidden') ? btnCancel : btnOk).focus();
                return false; // frena el envío hasta que el usuario acepte    
            }

            function cerrarModalDevolucion() {
                const modal = document.getElementById('modalDevolucion');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                formDevolucion = null;
            }

            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('modalDevolucion');
                if (!modal) return;

                document.getElementById('modalDevCancel').addEventListener('click', cerrarModalDevolucion);
                document.getElementById('modalDevOk').addEventListener('click', function() {
                    if (!formDevolucion) return;
                    const form = formDevolucion;
                    if (devolverTodo) {
                        // form.submit() no manda el botón que se apretó, así que agregamos "todo" a mano                
                        const h = document.createElement('input');
                        h.type = 'hidden';
                        h.name = 'todo';
                        h.value = '1';
                        form.appendChild(h);
                    }
                    this.disabled = true; // evita doble clic            
                    form.submit();
                });

                // Clic en el fondo oscuro o Esc = cancelar        
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) cerrarModalDevolucion();
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) cerrarModalDevolucion();
                });
            });

            // Configuración de Select2 y Lector de códigos de barra
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Seleccione un insumo/medicamento",
                    allowClear: true,
                    width: '100%'
                });

                const scanInput = document.getElementById('barcode_scan');
                if (scanInput) {
                    scanInput.focus();

                    const unidadesInput = document.getElementById('unidades_scan');
                    const formEscaneo = document.getElementById('formEscaneo');

                    scanInput.addEventListener('keydown', function(e) {
                        if (e.key !== 'Enter') return;
                        e.preventDefault();
                        if (scanInput.value.trim() === '') return;
                        unidadesInput.focus();
                        unidadesInput.select();
                    });

                    unidadesInput.addEventListener('keydown', function(e) {
                        if (e.key !== 'Enter') return;
                        e.preventDefault();
                        if (scanInput.value.trim() === '') {
                            scanInput.focus();
                            return;
                        }
                        formEscaneo.requestSubmit();
                    });

                    unidadesInput.addEventListener('input', function() {
                        if (unidadesInput.value.length > 4) {
                            scanInput.value = unidadesInput.value;
                            unidadesInput.value = 1;
                            scanInput.focus();
                        }
                    });

                    document.addEventListener('click', function(e) {
                        const tag = (e.target.tagName || '').toLowerCase();
                        const enCampo = ['input', 'select', 'textarea', 'button', 'a'].includes(tag) ||
                            e.target.closest('.select2-container, .select2-dropdown, button, a, label');
                        if (!enCampo) scanInput.focus();
                    });
                }
            });
        </script>
    @endpush
@endsection
