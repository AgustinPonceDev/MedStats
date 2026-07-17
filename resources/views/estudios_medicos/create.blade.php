@extends('layouts.app')

@section('title', 'Registrar Estudio de Diagnóstico')

@section('contenido')
    <div class="max-w-4xl mx-auto px-4 py-8">

        <h1 class="text-2xl font-bold bg-gradient-to-r from-[#1B7D8F] via-[#2BA8A0] to-[#245360] text-transparent bg-clip-text drop-shadow-md mb-6">
            Registrar Nuevo Estudio e Insumos
        </h1>

        <form action="{{ route('estudios_medicos.store') }}" method="POST"
            class="bg-white shadow rounded-lg p-6 border border-gray-200 space-y-6">
            @csrf

            {{-- Fila 1: Fecha y Paciente --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="fecha" class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                    <input type="date" name="fecha" id="fecha"
                        class="w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 focus:ring-2 focus:ring-[#1B7D8F]"
                        max="{{ now()->format('Y-m-d') }}" value="{{ old('fecha', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="md:col-span-2">
                    <label for="paciente_id" class="block text-sm font-medium text-gray-700 mb-1">Paciente</label>
                    <select name="paciente_id" id="paciente_id" class="select2 w-full" required>
                        <option value="">Seleccione un paciente</option>
                        @foreach ($pacientes as $paciente)
                            <option value="{{ $paciente->id }}" {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}>
                                {{ $paciente->apellido }}, {{ $paciente->nombre }} (DNI: {{ $paciente->dni }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Fila 2: I-A --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">I-A (Interno / Ambulatorio)</label>
                    <div class="flex items-center gap-3 h-[42px]">
                        <span id="ia_label_a" class="text-sm font-semibold text-blue-700">Ambulatorio</span>
                        <label class="relative inline-flex items-center cursor-pointer select-none">
                            <input type="checkbox" id="ia_toggle" class="sr-only peer"
                                {{ old('ia') == 'I' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-blue-200 rounded-full peer peer-checked:bg-amber-400 transition-colors duration-300"></div>
                            <div class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full shadow transition-transform duration-300 peer-checked:translate-x-5"></div>
                        </label>
                        <span id="ia_label_i" class="text-sm font-semibold text-amber-700">Internado</span>
                        <input type="hidden" name="ia" id="ia" value="{{ old('ia', 'A') }}">
                    </div>
                </div>

                {{-- Select 1: Modalidad (Rayos / Tomografía) --}}
                <div>
                    <label for="especialidad_id" class="block text-sm font-medium text-gray-700 mb-1">Modalidad</label>
                    <select name="especialidad_id" id="especialidad_id"
                        class="w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 focus:ring-2 focus:ring-[#1B7D8F]" required>
                        <option value="">Seleccione</option>
                        @foreach ($especialidades as $especialidad)
                            <option value="{{ $especialidad->id }}" {{ old('especialidad_id') == $especialidad->id ? 'selected' : '' }}>
                                {{ $especialidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Select 2: Estudio Real --}}
                <div>
                    <label for="estudio_id" class="block text-sm font-medium text-gray-700 mb-1">Estudio Real</label>
                    <select name="estudio_id" id="estudio_id"
                        class="w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 focus:ring-2 focus:ring-[#1B7D8F]" required disabled>
                        <option value="">Seleccione primero la modalidad</option>
                    </select>
                </div>
            </div>

            {{-- Fila 3: Regiones --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="regiones" class="block text-sm font-medium text-gray-700 mb-1">Cantidad de Regiones Vistas</label>
                    <input type="number" name="regiones" id="regiones" min="1" max="20"
                        class="w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 focus:ring-2 focus:ring-[#1B7D8F]"
                        placeholder="Ej: 2" value="{{ old('regiones', 1) }}" required>
                </div>
            </div>

            {{-- Sección de Insumos y Medios de Contraste --}}
            <div class="bg-gray-50 p-5 rounded-xl border border-gray-100">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-1">Medios de Contraste e Insumos</h3>
                <p class="text-xs text-gray-500 mb-4">Se descuentan automáticamente del stock de Diagnóstico por Imágenes al guardar.</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Contraste 50ml (Cantidad)
                            <span class="text-gray-400 font-normal">— disponible: {{ $stockInsumos['Contraste 50ml'] ?? 0 }}</span>
                        </label>
                        <div class="flex items-center justify-between gap-2 bg-white border border-gray-300 rounded-md shadow-sm px-2 py-1.5">
                            <button type="button" data-target="cont_50ml" data-step="-1"
                                class="stepper-btn w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-lg leading-none flex items-center justify-center transition-colors">−</button>
                            <input type="number" name="cont_50ml" id="cont_50ml" min="0"
                                class="w-14 text-center font-bold text-gray-800 border-0 focus:ring-0 p-0"
                                value="{{ old('cont_50ml', 0) }}">
                            <button type="button" data-target="cont_50ml" data-step="1"
                                class="stepper-btn w-8 h-8 rounded-full bg-[#1B7D8F] hover:bg-[#156370] text-white font-bold text-lg leading-none flex items-center justify-center transition-colors">+</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Contraste 100ml (Cantidad)
                            <span class="text-gray-400 font-normal">— disponible: {{ $stockInsumos['Contraste 100ml'] ?? 0 }}</span>
                        </label>
                        <div class="flex items-center justify-between gap-2 bg-white border border-gray-300 rounded-md shadow-sm px-2 py-1.5">
                            <button type="button" data-target="cont_100ml" data-step="-1"
                                class="stepper-btn w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-lg leading-none flex items-center justify-center transition-colors">−</button>
                            <input type="number" name="cont_100ml" id="cont_100ml" min="0"
                                class="w-14 text-center font-bold text-gray-800 border-0 focus:ring-0 p-0"
                                value="{{ old('cont_100ml', 0) }}">
                            <button type="button" data-target="cont_100ml" data-step="1"
                                class="stepper-btn w-8 h-8 rounded-full bg-[#1B7D8F] hover:bg-[#156370] text-white font-bold text-lg leading-none flex items-center justify-center transition-colors">+</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                            Jeringa Prellenada (Cantidad)
                            <span class="text-gray-400 font-normal">— disponible: {{ $stockInsumos['Jeringa Prellenada'] ?? 0 }}</span>
                        </label>
                        <div class="flex items-center justify-between gap-2 bg-white border border-gray-300 rounded-md shadow-sm px-2 py-1.5">
                            <button type="button" data-target="jeringa_prellenada" data-step="-1"
                                class="stepper-btn w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-lg leading-none flex items-center justify-center transition-colors">−</button>
                            <input type="number" name="jeringa_prellenada" id="jeringa_prellenada" min="0"
                                class="w-14 text-center font-bold text-gray-800 border-0 focus:ring-0 p-0"
                                value="{{ old('jeringa_prellenada', 0) }}">
                            <button type="button" data-target="jeringa_prellenada" data-step="1"
                                class="stepper-btn w-8 h-8 rounded-full bg-[#1B7D8F] hover:bg-[#156370] text-white font-bold text-lg leading-none flex items-center justify-center transition-colors">+</button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label for="descartables" class="block text-xs font-semibold text-gray-600 mb-1">Descartables utilizados</label>
                        <input type="text" name="descartables" id="descartables"
                            class="w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:ring-2 focus:ring-[#1B7D8F]"
                            placeholder="Ej: Guantes, jeringa simple, gasas..." value="{{ old('descartables') }}">
                    </div>

                    <div>
                        <label for="otros_agujas" class="block text-xs font-semibold text-gray-600 mb-1">Otros y Agujas de Punción</label>
                        <input type="text" name="otros_agujas" id="otros_agujas"
                            class="w-full rounded-md border border-gray-300 shadow-sm px-3 py-2 focus:ring-2 focus:ring-[#1B7D8F]"
                            placeholder="Ej: Aguja espinal 22G, Abocath..." value="{{ old('otros_agujas') }}">
                    </div>
                </div>
            </div>

            {{-- Fila: Médico Solicitante (Ahora hereda automáticamente el estilo de arriba) --}}
            <div>
                <label for="medico_solicitante_id" class="block text-sm font-medium text-gray-700 mb-1">Médico Solicitante</label>
                <select name="medico_solicitante_id" id="medico_solicitante_id" class="select2 w-full" required>
                    <option value="">Seleccione el médico</option>
                    @foreach ($medicos as $medico)
                        <option value="{{ $medico->id }}" {{ old('medico_solicitante_id') == $medico->id ? 'selected' : '' }}>
                            {{ $medico->apellido }}, {{ $medico->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Fila: Resultado --}}
            <div>
                <label for="resultado" class="block text-sm font-medium text-gray-700 mb-1">Observaciones / Informe</label>
                <textarea name="resultado" id="resultado" rows="3"
                    class="w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 focus:ring-2 focus:ring-[#1B7D8F]"
                    placeholder="Diagnóstico preliminar o notas internas...">{{ old('resultado') }}</textarea>
            </div>

            {{-- Botones --}}
            <div class="flex justify-between pt-4">
                <a href="{{ route('estudios_medicos.index') }}"
                    class="btn btn-outline-danger px-5 py-2 rounded shadow-sm no-underline">
                    Cancelar
                </a>
                <button type="submit"
                    class="bg-gradient-to-r from-[#1B7D8F] to-[#2BA8A0] text-white px-6 py-2 rounded-xl font-semibold shadow-md hover:scale-105 transition duration-300">
                    Guardar Práctica
                </button>
            </div>
        </form>
    </div>
<style>
    /* 1. Contenedor principal de Select2 */
    .select2-container--default .select2-selection--single {
        border: 1px solid #d1d5db !important; /* border-gray-300 */
        height: 42px !important; /* Altura idéntica */
        border-radius: 0.375rem !important; /* rounded-md */
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important; /* shadow-sm */
        display: flex !important;
        align-items: center !important;
        background-color: #ffffff !important;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
    }

    /* 2. Formato de texto idéntico al input nativo */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px !important;
        padding-left: 1rem !important; /* px-4 */
        padding-right: 2.5rem !important;
        color: #1f2937 !important; /* text-gray-800 - igual a Modalidad */
        font-size: 0.95rem !important; /* Ajustado para igualar el tamaño visual */
        font-weight: 400 !important;
        width: 100% !important;
    }

    /* Si es un placeholder (por ejemplo, "Seleccione un paciente"), le damos un color gris más suave */
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6b7280 !important; /* text-gray-500 */
    }

    /* 3. Reemplazar la flecha fea por una idéntica a la de Tailwind */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        top: 1px !important;
        right: 12px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* Eliminamos el triángulo gris clásico de Select2 */
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border: none !important; /* Borra el triángulo */
        
        /* Creamos la flecha angular (chevron) con CSS puro */
        width: 6px !important;
        height: 6px !important;
        border-right: 2px solid #1f2937 !important; /* Color oscuro */
        border-bottom: 2px solid #1f2937 !important;
        transform: rotate(45deg) !important; /* Lo gira para hacer la "V" */
        margin-top: -3px !important; /* Ajuste fino de altura */
        transition: transform 0.2s ease;
    }

    /* Rotar la flecha hacia arriba cuando el select esté abierto */
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        transform: rotate(-135deg) !important;
        margin-top: 1px !important;
    }

    /* 4. Efecto de Foco Activo */
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #1B7D8F !important;
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(27, 125, 143, 0.3) !important;
    }

    /* 5. Dropdown de opciones */
    .select2-dropdown {
        border-color: #d1d5db !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }
</style>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            if ($.fn.select2) {
                $('.select2').select2({ width: '100%' });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // ---------- Toggle I-A ----------
            const toggle = document.getElementById('ia_toggle');
            const hidden = document.getElementById('ia');
            const labelA = document.getElementById('ia_label_a');
            const labelI = document.getElementById('ia_label_i');

            function syncIaLabels() {
                const esInternado = toggle.checked;
                hidden.value = esInternado ? 'I' : 'A';
                labelA.classList.toggle('opacity-40', esInternado);
                labelI.classList.toggle('opacity-40', !esInternado);
            }
            if (toggle) {
                toggle.addEventListener('change', syncIaLabels);
                syncIaLabels();
            }

            // ---------- Contadores +/- de insumos fijos ----------
            document.querySelectorAll('.stepper-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const input = document.getElementById(this.dataset.target);
                    const step = parseInt(this.dataset.step, 10);
                    let value = parseInt(input.value, 10);
                    if (isNaN(value)) value = 0;
                    value = Math.max(0, value + step);
                    input.value = value;
                });
            });

            // ---------- Select encadenado: Modalidad -> Estudio Real ----------
            const especialidadSelect = document.getElementById('especialidad_id');
            const estudioSelect = document.getElementById('estudio_id');
            const oldEstudioId = "{{ old('estudio_id') }}";

            function cargarEstudios(especialidadId, seleccionado) {
                estudioSelect.innerHTML = '<option value="">Cargando...</option>';
                estudioSelect.disabled = true;

                if (!especialidadId) {
                    estudioSelect.innerHTML = '<option value="">Seleccione primero la modalidad</option>';
                    return;
                }

                fetch(`/estudios-medicos/especialidad/${especialidadId}/estudios`)
                    .then(res => res.json())
                    .then(data => {
                        estudioSelect.innerHTML = '<option value="">Seleccione un estudio</option>';
                        data.forEach(function(item) {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.textContent = item.nombre;
                            if (seleccionado && String(seleccionado) === String(item.id)) {
                                opt.selected = true;
                            }
                            estudioSelect.appendChild(opt);
                        });
                        estudioSelect.disabled = false;
                    })
                    .catch(() => {
                        estudioSelect.innerHTML = '<option value="">Error al cargar los estudios</option>';
                    });
            }

            especialidadSelect.addEventListener('change', function() {
                cargarEstudios(this.value, null);
            });

            if (especialidadSelect.value) {
                cargarEstudios(especialidadSelect.value, oldEstudioId);
            }
        });
    </script>
@endpush