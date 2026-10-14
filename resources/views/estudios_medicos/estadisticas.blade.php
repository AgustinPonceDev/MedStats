@extends('layouts.app_estadisticas')
@section('contenido')
    <div class="container-fluid py-4">
        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
                    Estadísticas de Diagnóstico por Imágenes
                </h2>
                <p class="text-gray-500 mt-1">Resumen y métricas clave de Estudios Médicos</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('stocks.estadisticasstock', ['servicio_id' => $servicioImagenId]) }}"
                    class="btn bg-white text-gray-700 shadow-sm hover:shadow-md border border-gray-200 d-flex align-items-center px-4 py-2 rounded-lg transition-all">
                    <i class="bi bi-box-seam me-2 text-[#1B7D8F]"></i>
                    <span class="font-medium">Estadísticas de Stock</span>
                </a>
            </div>
        </div>

        {{-- Filtros --}}
        <form method="GET" action="{{ route('estudios_medicos.estadisticas') }}"
            class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-5 transition-all hover:shadow-md">
            @if (request('anio'))
                <input type="hidden" name="anio" value="{{ request('anio') }}">
            @endif
            <div class="row g-4 align-items-end">
                <div class="col-md-3">
                    <label for="desde" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Desde</label>
                    <input type="date" name="desde" id="desde" value="{{ request('desde') }}"
                        class="form-control bg-gray-50 border-gray-200 rounded-lg focus:ring-[#1B7D8F] focus:border-[#1B7D8F] text-gray-700">
                </div>

                <div class="col-md-3">
                    <label for="hasta" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Hasta</label>
                    <input type="date" name="hasta" id="hasta" value="{{ request('hasta') }}"
                        class="form-control bg-gray-50 border-gray-200 rounded-lg focus:ring-[#1B7D8F] focus:border-[#1B7D8F] text-gray-700">
                </div>

                <div class="col-md-2">
                    <label for="especialidad_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Modalidad</label>
                    <select name="especialidad_id" id="especialidad_id"
                        class="form-select bg-gray-50 border-gray-200 rounded-lg focus:ring-[#1B7D8F] focus:border-[#1B7D8F] text-gray-700"
                        onchange="this.form.submit()">
                        <option value="">Todas</option>
                        @foreach ($modalidadesDisponibles as $mod)
                            <option value="{{ $mod->id }}" {{ $mod->id == $modalidadId ? 'selected' : '' }}>
                                {{ $mod->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="medico_id" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Médico solicitante</label>
                    <select name="medico_id" id="medico_id"
                        class="form-select bg-gray-50 border-gray-200 rounded-lg focus:ring-[#1B7D8F] focus:border-[#1B7D8F] text-gray-700">
                        <option value="">Todos</option>
                        @foreach ($medicosDisponibles as $medico)
                            <option value="{{ $medico->id }}" {{ $medico->id == $medicoId ? 'selected' : '' }}>
                                {{ $medico->apellido }}, {{ $medico->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit"
                        class="btn w-100 rounded-lg d-flex align-items-center justify-content-center gap-2 text-white font-medium shadow-md hover:shadow-lg transition-all"
                        style="background: linear-gradient(135deg, #1B7D8F 0%, #245360 100%);">
                        <i class="bi bi-funnel-fill"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>

        @if ($errors->has('desde') || $errors->has('hasta'))
            <div class="alert alert-warning rounded-lg shadow-sm border-0 mb-4 d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-3 text-warning fs-4"></i>
                <div>
                    <strong class="d-block text-gray-800">Atención</strong>
                    <ul class="mb-0 ps-3 text-sm text-gray-600">
                        @foreach ($errors->get('desde') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        @foreach ($errors->get('hasta') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @if (request('desde') && request('hasta'))
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 mb-5 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center text-blue-800">
                    <i class="bi bi-calendar-check me-2"></i>
                    <span class="font-medium me-2">Filtro activo:</span>
                    <span>{{ \Carbon\Carbon::parse(request('desde'))->format('d/m/Y') }} - {{ \Carbon\Carbon::parse(request('hasta'))->format('d/m/Y') }}</span>
                </div>
                <a href="{{ route('estudios_medicos.estadisticas') }}"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">
                    Limpiar filtros
                </a>
            </div>
        @endif

        @if ($total === 0)
            <div class="alert alert-info rounded-lg shadow-sm border-0 d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                <div>No hay estudios registrados para el período y los filtros seleccionados.</div>
            </div>
        @else
            {{-- Fila 1: Resumen general + Top médicos --}}
            <div class="row g-4 mb-5">
                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="800">
                    <div class="card border-0 shadow-sm rounded-xl h-100 overflow-hidden">
                        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="font-bold text-gray-800 mb-1">Resumen General</h5>
                                <p class="text-sm text-gray-500 mb-0">
                                    {{ request('desde') && request('hasta') ? 'Métricas del período seleccionado' : 'Métricas del año ' . $anioSeleccionado }}
                                </p>
                            </div>
                            <form method="GET" action="{{ route('estudios_medicos.estadisticas') }}">
                                @foreach (['especialidad_id', 'medico_id'] as $campo)
                                    @if (request($campo))
                                        <input type="hidden" name="{{ $campo }}" value="{{ request($campo) }}">
                                    @endif
                                @endforeach
                                <select name="anio" id="anio"
                                    class="form-select form-select-sm bg-gray-50 border-gray-200 text-gray-600 font-medium rounded-lg"
                                    onchange="this.form.submit()">
                                    @foreach ($aniosDisponibles as $anio)
                                        <option value="{{ $anio }}" {{ $anio == $anioSeleccionado ? 'selected' : '' }}>{{ $anio }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                        <div class="card-body px-4">
                            <div class="row g-3 mb-4">
                                <div class="col-4">
                                    <div class="p-3 rounded-xl bg-blue-50 text-center h-100 border border-blue-100">
                                        <div class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1">Total</div>
                                        <div class="text-2xl font-bold text-gray-800">{{ $total }}</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 rounded-xl bg-emerald-50 text-center h-100 border border-emerald-100">
                                        <div class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Mensual</div>
                                        <div class="text-2xl font-bold text-gray-800">{{ $promedioMensual }}</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 rounded-xl bg-amber-50 text-center h-100 border border-amber-100">
                                        <div class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-1">Semanal</div>
                                        <div class="text-2xl font-bold text-gray-800">{{ $promedioSemanal }}</div>
                                    </div>
                                </div>
                            </div>

                            <div style="height: 250px;">
                                <canvas id="estudiosPorMes"></canvas>
                            </div>

                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-link text-decoration-none text-[#1B7D8F] font-medium text-sm"
                                    data-bs-toggle="modal" data-bs-target="#modalMeses">
                                    Ver tabla detallada <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="card border-0 shadow-sm rounded-xl h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="font-bold text-gray-800 mb-1">Top Médicos Solicitantes</h5>
                            <p class="text-sm text-gray-500 mb-0">Profesionales que más estudios piden</p>
                        </div>
                        <div class="card-body px-4">
                            <div class="row align-items-center h-100">
                                <div class="col-md-7">
                                    <div class="d-flex flex-column gap-3">
                                        @foreach ($porMedico->take(5) as $index => $item)
                                            <div class="d-flex align-items-center justify-content-between p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="d-flex align-items-center justify-content-center w-6 h-6 rounded-full bg-gray-100 text-xs font-bold text-gray-500">{{ $index + 1 }}</span>
                                                    <span class="text-sm font-medium text-gray-700">
                                                        @if ($item->medico_solicitante)
                                                            {{ $item->medico_solicitante->apellido }}, {{ $item->medico_solicitante->nombre }}
                                                        @else
                                                            Sin asignar
                                                        @endif
                                                    </span>
                                                </div>
                                                <span class="badge bg-blue-100 text-blue-700 rounded-pill px-3 py-1">{{ $item->total }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-link text-decoration-none text-[#1B7D8F] font-medium text-sm mt-3 ps-0"
                                        data-bs-toggle="modal" data-bs-target="#modalMedicos">
                                        Ver listado completo <i class="bi bi-arrow-right ms-1"></i>
                                    </button>
                                </div>
                                <div class="col-md-5 d-flex justify-content-center">
                                    <div style="width: 200px; height: 200px;">
                                        <canvas id="graficoMedicos"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fila 2: Top 5 estudios --}}
            <div class="row g-4 mb-5">
                <div class="col-md-12" data-aos="fade-up" data-aos-duration="800">
                    <div class="card border-0 shadow-sm rounded-xl h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="font-bold text-gray-800 mb-1">Top 5 Estudios Más Realizados</h5>
                            <p class="text-sm text-gray-500 mb-0">Estudios con mayor frecuencia, con las regiones vistas</p>
                        </div>
                        <div class="card-body px-4">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <div class="d-flex flex-column gap-3">
                                        @foreach ($topEstudios->take(5) as $index => $item)
                                            <div class="d-flex align-items-center justify-content-between p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="d-flex align-items-center justify-content-center w-6 h-6 rounded-full bg-teal-100 text-xs font-bold text-teal-700">{{ $index + 1 }}</span>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-700">{{ $item->nombre }}</div>
                                                        <div class="text-xs text-gray-400">
                                                            {{ optional(optional($item->estudio)->especialidad)->nombre ?? 'Sin modalidad' }}
                                                            · {{ $item->regiones_total }} {{ $item->regiones_total == 1 ? 'región' : 'regiones' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="badge bg-teal-50 text-teal-700 rounded-pill px-3 py-1 font-bold">{{ $item->total }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-link text-decoration-none text-[#1B7D8F] font-medium text-sm mt-3 ps-0"
                                        data-bs-toggle="modal" data-bs-target="#modalEstudios">
                                        Ver todos los estudios <i class="bi bi-arrow-right ms-1"></i>
                                    </button>
                                </div>
                                <div class="col-md-5 d-flex justify-content-center">
                                    <div style="width: 220px; height: 220px;">
                                        <canvas id="graficoEstudios"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fila 3: Por modalidad con los estudios reales --}}
            <div class="row g-4 mb-5">
                <div class="col-md-12" data-aos="fade-up" data-aos-duration="800">
                    <div class="card border-0 shadow-sm rounded-xl h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="font-bold text-gray-800 mb-1">Por Modalidad</h5>
                            <p class="text-sm text-gray-500 mb-0">Rayos X y Tomografía, con los estudios reales de cada una</p>
                        </div>
                        <div class="card-body px-4">
                            <div class="row g-4 align-items-start">
                                <div class="col-lg-3 d-flex flex-column align-items-center">
                                    <div style="width: 180px; height: 180px;">
                                        <canvas id="graficoModalidad"></canvas>
                                    </div>
                                    <div class="w-100 mt-3 d-flex flex-column gap-1">
                                        @foreach ($modalidades as $mod)
                                            <div class="d-flex justify-content-between text-sm text-gray-600">
                                                <span>{{ $mod->nombre }}</span>
                                                <span class="font-bold text-gray-900">{{ round(($mod->total / $total) * 100) }}%</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-lg-9">
                                    <div class="row g-4">
                                        @foreach ($modalidades as $mod)
                                            <div class="col-md-6 mt-3">
                                                <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 h-100">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <span class="font-bold text-gray-800">{{ $mod->nombre }}</span>
                                                        <span class="badge bg-blue-100 text-blue-700 rounded-pill px-3 py-1">{{ $mod->total }}</span>
                                                    </div>
                                                    <div class="d-flex flex-column gap-3">
                                                        @foreach ($mod->estudios->take(5) as $est)
                                                            <div>
                                                                <div class="d-flex justify-content-between text-sm text-gray-700 mb-1">
                                                                    <span>{{ $est->nombre }}</span>
                                                                    <span class="font-bold text-gray-900">{{ $est->total }}</span>
                                                                </div>
                                                                <div class="progress" style="height: 6px;">
                                                                    <div class="progress-bar" role="progressbar"
                                                                        style="width: {{ round(($est->total / max(1, $mod->total)) * 100) }}%; background-color: #1B7D8F;"></div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fila 4: Día de la semana / Ambulatorios vs Internados / Contraste --}}
            <div class="row g-4 mb-5">
                <div class="col-lg-4" data-aos="fade-up" data-aos-duration="800">
                    <div class="card border-0 shadow-sm rounded-xl h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="font-bold text-gray-800 mb-1">Carga por Día de la Semana</h5>
                            <p class="text-sm text-gray-500 mb-0">Cuándo hay más estudios</p>
                        </div>
                        <div class="card-body px-4">
                            <div style="height: 240px;">
                                <canvas id="graficoDias"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="card border-0 shadow-sm rounded-xl h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="font-bold text-gray-800 mb-1">Ambulatorios vs Internados</h5>
                            <p class="text-sm text-gray-500 mb-0">Origen de los pacientes</p>
                        </div>
                        <div class="card-body px-4">
                            <div class="d-flex gap-3 mb-3">
                                <div class="flex-fill p-3 rounded-xl bg-blue-50 border border-blue-100 text-center">
                                    <div class="text-xs font-bold text-blue-600 uppercase tracking-wider">Ambulatorios</div>
                                    <div class="text-2xl font-bold text-gray-800">{{ $ambulatorios }}</div>
                                </div>
                                <div class="flex-fill p-3 rounded-xl bg-amber-50 border border-amber-100 text-center">
                                    <div class="text-xs font-bold text-amber-600 uppercase tracking-wider">Internados</div>
                                    <div class="text-2xl font-bold text-gray-800">{{ $internados }}</div>
                                </div>
                            </div>
                            <div style="height: 150px;">
                                <canvas id="graficoIA"></canvas>
                            </div>
                            <div class="text-center mt-2">
                                <span class="text-sm text-gray-500">Tasa de internación</span>
                                <div class="text-2xl font-bold text-gray-800">
                                    {{ $internados + $ambulatorios > 0 ? round(($internados / ($internados + $ambulatorios)) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div class="card border-0 shadow-sm rounded-xl h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="font-bold text-gray-800 mb-1">Contraste y Regiones</h5>
                            <p class="text-sm text-gray-500 mb-0">Insumos consumidos en el período</p>
                        </div>
                        <div class="card-body px-4">
                            <div class="p-3 rounded-xl bg-teal-50 border border-teal-100 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-teal-700 font-medium">Estudios con contraste</span>
                                    <span class="text-2xl font-bold text-teal-700">{{ $conContraste }}</span>
                                </div>
                                <div class="text-xs text-teal-700 mt-1">{{ round(($conContraste / $total) * 100, 1) }}% del total</div>
                            </div>
                            <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                                <li class="d-flex justify-content-between text-sm text-gray-700 border-b border-gray-50 pb-2">
                                    <span>Contraste 50ml</span><span class="font-bold text-gray-900">{{ $cont50 }} u.</span>
                                </li>
                                <li class="d-flex justify-content-between text-sm text-gray-700 border-b border-gray-50 pb-2">
                                    <span>Contraste 100ml</span><span class="font-bold text-gray-900">{{ $cont100 }} u.</span>
                                </li>
                                <li class="d-flex justify-content-between text-sm text-gray-700 border-b border-gray-50 pb-2">
                                    <span>Jeringas prellenadas</span><span class="font-bold text-gray-900">{{ $jeringas }} u.</span>
                                </li>
                                <li class="d-flex justify-content-between text-sm text-gray-700 border-b border-gray-50 pb-2">
                                    <span>Regiones vistas</span><span class="font-bold text-gray-900">{{ $totalRegiones }}</span>
                                </li>
                                <li class="d-flex justify-content-between text-sm text-gray-700">
                                    <span>Regiones por estudio</span><span class="font-bold text-gray-900">{{ $promedioRegiones }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modales --}}
            @push('modales')
                {{-- Modal Meses --}}
                <div class="modal fade" id="modalMeses" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-xl">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title font-bold text-gray-800">Detalle Mensual</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-hover">
                                    <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                        <tr><th class="border-0 rounded-start">Mes</th><th class="border-0 text-end rounded-end">Total</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($porMes as $item)
                                            <tr>
                                                <td class="border-gray-100">{{ $item->mes_nombre }}</td>
                                                <td class="border-gray-100 text-end font-bold text-[#1B7D8F]">{{ $item->total }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Médicos --}}
                <div class="modal fade" id="modalMedicos" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow-lg rounded-xl">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title font-bold text-gray-800">Listado de Médicos Solicitantes</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                            <tr>
                                                <th class="border-0 rounded-start py-3">Profesional</th>
                                                <th class="border-0 text-end rounded-end py-3">Estudios solicitados</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($porMedico as $item)
                                                <tr>
                                                    <td class="border-gray-100 py-3">
                                                        <div class="font-medium text-gray-800">{{ optional($item->medico_solicitante)->apellido ?? 'Sin asignar' }}</div>
                                                        <div class="text-sm text-gray-500">{{ optional($item->medico_solicitante)->nombre }}</div>
                                                    </td>
                                                    <td class="border-gray-100 text-end py-3">
                                                        <span class="badge bg-blue-50 text-blue-700 rounded-pill px-3 py-2">{{ $item->total }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Estudios --}}
                <div class="modal fade" id="modalEstudios" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 shadow-lg rounded-xl">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title font-bold text-gray-800">Listado de Estudios Realizados</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                            <tr>
                                                <th class="border-0 rounded-start py-3">Estudio</th>
                                                <th class="border-0 py-3">Modalidad</th>
                                                <th class="border-0 text-end py-3">Regiones</th>
                                                <th class="border-0 text-end rounded-end py-3">Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topEstudios as $item)
                                                <tr>
                                                    <td class="border-gray-100 py-3 font-medium text-gray-800">{{ $item->nombre }}</td>
                                                    <td class="border-gray-100 py-3 text-gray-500">{{ optional(optional($item->estudio)->especialidad)->nombre ?? '-' }}</td>
                                                    <td class="border-gray-100 py-3 text-end text-gray-600">{{ $item->regiones_total }}</td>
                                                    <td class="border-gray-100 text-end py-3">
                                                        <span class="badge bg-teal-50 text-teal-700 rounded-pill px-3 py-2 font-bold">{{ $item->total }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endpush

            @push('scripts')
                <script src="{{ asset('vendor/js/chart.js') }}"></script>
                <script src="{{ asset('vendor/js/bootstrap-5.3.0.bundle.min.js') }}"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Chart.defaults.font.family = "'Inter', sans-serif";
                        Chart.defaults.color = '#64748b';

                        const commonOptions = {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { usePointStyle: true, padding: 20, boxWidth: 8 }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                    titleColor: '#1e293b',
                                    bodyColor: '#475569',
                                    borderColor: '#e2e8f0',
                                    borderWidth: 1,
                                    padding: 12,
                                    displayColors: true,
                                    boxPadding: 4
                                }
                            }
                        };

                        const barOptions = {
                            ...commonOptions,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, ticks: { precision: 0, padding: 10 }, grid: { color: '#f1f5f9', drawBorder: false } },
                                x: { grid: { display: false, drawBorder: false } }
                            }
                        };

                        const donutColors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'];
                        const donutOptions = {
                            ...commonOptions,
                            cutout: '75%',
                            plugins: { ...commonOptions.plugins, legend: { display: false } }
                        };

                        const donut = (id, labels, values, colors = donutColors, options = donutOptions) =>
                            new Chart(document.getElementById(id), {
                                type: 'doughnut',
                                data: { labels: labels, datasets: [{ data: values, backgroundColor: colors, borderWidth: 0 }] },
                                options: options
                            });

                        // Estudios por mes
                        new Chart(document.getElementById('estudiosPorMes'), {
                            type: 'bar',
                            data: {
                                labels: @json($porMesLabels),
                                datasets: [{ label: 'Estudios', data: @json($porMesValores), backgroundColor: '#1B7D8F', borderRadius: 6, barThickness: 24 }]
                            },
                            options: barOptions
                        });

                        // Carga por día de la semana
                        new Chart(document.getElementById('graficoDias'), {
                            type: 'bar',
                            data: {
                                labels: @json($diaLabels),
                                datasets: [{ label: 'Estudios', data: @json($diaValores), backgroundColor: '#2BA8A0', borderRadius: 6 }]
                            },
                            options: barOptions
                        });

                        donut('graficoMedicos', @json($medicoLabels), @json($medicoValores));
                        donut('graficoEstudios', @json($estudioLabels), @json($estudioValores));
                        donut('graficoModalidad', @json($modalidadLabels), @json($modalidadValores));

                        // Ambulatorios vs Internados
                        donut('graficoIA', ['Ambulatorios', 'Internados'], [{{ $ambulatorios }}, {{ $internados }}],
                            ['#3b82f6', '#f59e0b'], { ...commonOptions, cutout: '60%' });

                        if (window.AOS) AOS.init();
                    });
                </script>
            @endpush
        @endif
    </div>
@endsection
