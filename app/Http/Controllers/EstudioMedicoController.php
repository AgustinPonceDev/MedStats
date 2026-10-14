<?php

namespace App\Http\Controllers;

use App\Models\EstudioMedico;
use App\Http\Requests\StoreEstudioMedicoRequest;
use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Empleado;
use App\Models\Especialidad;
use App\Models\Estudio;
use App\Models\Servicio;
use App\Models\Stock;
use App\Models\Medicamento;
use App\Models\Historial_stock;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstudioMedicoController extends Controller
{
    // Nombre del servicio de Diagnóstico por Imágenes, tal como está cargado en la tabla servicios
    private const SERVICIO_DIAGNOSTICO_IMAGEN = 'Diagnóstico por imágenes';

    // Nombres de los medicamentos/insumos que representan cada contador del formulario.
    // Tienen que coincidir con el "nombre" cargado en /medicamentos (se crean solos si no existen).
    private const INSUMO_CONTRASTE_50 = 'Contraste 50ml';
    private const INSUMO_CONTRASTE_100 = 'Contraste 100ml';
    private const INSUMO_JERINGA = 'Jeringa Prellenada';

    public function index()
    {
        $estudios = EstudioMedico::with(['paciente', 'medico_solicitante', 'especialidad', 'estudio'])
            ->orderBy('fecha', 'asc')
            ->orderBy('hora_estudio', 'asc')
            ->get();

        return view('estudios_medicos.index', compact('estudios'));
    }

    public function create()
    {
        $pacientes = Paciente::orderBy('apellido')->orderBy('nombre')->get();
        $medicos = Empleado::orderBy('apellido')->orderBy('nombre')->get();
        $especialidades = Especialidad::modalidadesImagen()->orderBy('nombre')->get();
        $stockInsumos = $this->stockDeInsumosFijos();

        return view('estudios_medicos.create', compact('pacientes', 'medicos', 'especialidades', 'stockInsumos'));
    }

    // Endpoint para el select encadenado: estudios disponibles según la especialidad/modalidad elegida
    public function estudiosPorEspecialidad(Especialidad $especialidad)
    {
        $estudios = $especialidad->estudios()->orderBy('nombre')->get(['id', 'nombre']);

        return response()->json($estudios);
    }

    public function store(StoreEstudioMedicoRequest $request)
    {
        $data = $request->validated();
        $estudioReal = Estudio::findOrFail($data['estudio_id']);

        // Mantener sincronizado el nombre del estudio guardado en el registro
        $data['tipo_estudio'] = $estudioReal->nombre;

        DB::beginTransaction();
        try {
            $estudioMedico = EstudioMedico::create($data);
            // ...

            $this->descontarInsumoFijo($estudioMedico, self::INSUMO_CONTRASTE_50, (int) $data['cont_50ml']);
            $this->descontarInsumoFijo($estudioMedico, self::INSUMO_CONTRASTE_100, (int) $data['cont_100ml']);
            $this->descontarInsumoFijo($estudioMedico, self::INSUMO_JERINGA, (int) $data['jeringa_prellenada']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['insumos' => $e->getMessage()])
                ->withInput();
        }

        return redirect()->route('estudios_medicos.index')
            ->with('success', 'Estudio médico registrado correctamente.');
    }

    public function show(int $id)
    {
        $estudio = EstudioMedico::with(['paciente', 'medico_solicitante', 'especialidad', 'estudio', 'movimientos_stock.get_stock.get_medicamento'])
            ->findOrFail($id);

        return view('estudios_medicos.show', compact('estudio'));
    }

    public function edit(EstudioMedico $estudioMedico)
    {
        $pacientes = Paciente::orderBy('apellido')->orderBy('nombre')->get();
        $medicos = Empleado::orderBy('apellido')->orderBy('nombre')->get();
        $especialidades = Especialidad::modalidadesImagen()->orderBy('nombre')->get();
        // Estudios de la modalidad ya seleccionada, para que el select venga precargado sin esperar el JS
        $estudios = $estudioMedico->especialidad_id
            ? Estudio::where('especialidad_id', $estudioMedico->especialidad_id)->orderBy('nombre')->get()
            : collect();
        $estudio = $estudioMedico;
        $stockInsumos = $this->stockDeInsumosFijos();

        return view('estudios_medicos.edit', compact('estudio', 'pacientes', 'medicos', 'especialidades', 'estudios', 'stockInsumos'));
    }

    public function update(Request $request, EstudioMedico $estudioMedico)
    {
        $data = $request->validate([
            'fecha'                 => 'required|date|before_or_equal:today',
            'hora_estudio'          => 'nullable|date_format:H:i|before_or_equal:today',
            'paciente_id'           => 'required|exists:pacientes,id',
            'ia'                    => 'nullable|string|max:20',
            'especialidad_id'       => ['required', 'integer', \Illuminate\Validation\Rule::exists('especialidads', 'id')->where(fn($q) => $q->where('es_modalidad_imagen', true))],
            'estudio_id'            => ['required', 'integer', \Illuminate\Validation\Rule::exists('estudios', 'id')->where(fn($q) => $q->where('especialidad_id', $request->input('especialidad_id')))],
            'regiones'              => 'required|integer|min:1|max:20',
            'cont_50ml'             => 'required|integer|min:0',
            'cont_100ml'            => 'required|integer|min:0',
            'jeringa_prellenada'    => 'required|integer|min:0',
            'descartables'          => 'nullable|string|max:255',
            'otros_agujas'          => 'nullable|string|max:255',
            'medico_solicitante_id' => 'required|exists:empleados,id',
            'resultado'             => 'nullable|string',
        ], [
            'estudio_id.exists' => 'El estudio seleccionado no corresponde a la modalidad elegida.',
        ]);

        $estudioReal = Estudio::findOrFail($data['estudio_id']);
        $data['tipo_estudio'] = $estudioReal->nombre;
        DB::beginTransaction();
        try {
            // Evaluamos los deltas para cada uno de los 3 insumos fijos
            $this->procesarDeltaInsumo($estudioMedico, self::INSUMO_CONTRASTE_50, 'cont_50ml', (int) $data['cont_50ml']);
            $this->procesarDeltaInsumo($estudioMedico, self::INSUMO_CONTRASTE_100, 'cont_100ml', (int) $data['cont_100ml']);
            $this->procesarDeltaInsumo($estudioMedico, self::INSUMO_JERINGA, 'jeringa_prellenada', (int) $data['jeringa_prellenada']);

            // Una vez que los stocks se actualizaron correctamente, guardamos el estudio
            $estudioMedico->update($data);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['insumos' => $e->getMessage()])
                ->withInput();
        }

        return redirect()->route('estudios_medicos.index')
            ->with('success', 'El estudio médico y su stock se actualizaron correctamente.');
    }

    public function destroy(EstudioMedico $estudioMedico)
    {
        DB::beginTransaction();
        try {
            // Al eliminar el estudio, devolvemos todos los insumos consumidos al stock
            $this->devolverInsumoFijo($estudioMedico, self::INSUMO_CONTRASTE_50, (int) $estudioMedico->cont_50ml);
            $this->devolverInsumoFijo($estudioMedico, self::INSUMO_CONTRASTE_100, (int) $estudioMedico->cont_100ml);
            $this->devolverInsumoFijo($estudioMedico, self::INSUMO_JERINGA, (int) $estudioMedico->jeringa_prellenada);

            $estudioMedico->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'No se pudo eliminar el estudio: ' . $e->getMessage()]);
        }

        return redirect()->route('estudios_medicos.index')
            ->with('success', 'Estudio médico eliminado y stock devuelto correctamente.');
    }

    /**
     * Estadísticas de Diagnóstico por Imágenes (misma estructura que Estadísticas de Cirugías).
     * Sin rango de fechas muestra el año elegido (por defecto el actual o el último con datos).
     */
    public function estadisticas(Request $request)
    {
        $validated = $request->validate([
            'desde'           => 'nullable|date|before_or_equal:today',
            'hasta'           => 'nullable|date|after_or_equal:desde|before_or_equal:today',
            'anio'            => 'nullable|integer|min:2000|max:2100',
            'especialidad_id' => ['nullable', 'integer', \Illuminate\Validation\Rule::exists('especialidads', 'id')->where(fn($q) => $q->where('es_modalidad_imagen', true))],
            'medico_id'       => 'nullable|integer|exists:empleados,id',
        ], [
            'desde.date'            => 'La fecha de inicio no tiene un formato válido.',
            'desde.before_or_equal' => 'La fecha de inicio no puede ser futura.',
            'hasta.date'            => 'La fecha de fin no tiene un formato válido.',
            'hasta.after_or_equal'  => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'hasta.before_or_equal' => 'La fecha de fin no puede ser futura.',
        ]);

        $desde       = $validated['desde'] ?? null;
        $hasta       = $validated['hasta'] ?? null;
        $modalidadId = $validated['especialidad_id'] ?? null;
        $medicoId    = $validated['medico_id'] ?? null;
        $porRango    = $desde && $hasta;

        $aniosDisponibles = EstudioMedico::select(DB::raw('YEAR(fecha) as anio'))
            ->distinct()
            ->orderByDesc('anio')
            ->pluck('anio');

        $anioSeleccionado = (int) ($validated['anio']
            ?? ($aniosDisponibles->contains(now()->year) ? now()->year : ($aniosDisponibles->first() ?? now()->year)));

        // Base reutilizable con los filtros activos
        $base = EstudioMedico::query()
            ->when(
                $porRango,
                fn($q) => $q->whereBetween('fecha', [$desde, $hasta]),
                fn($q) => $q->whereYear('fecha', $anioSeleccionado)
            )
            ->when($modalidadId, fn($q, $v) => $q->where('especialidad_id', $v))
            ->when($medicoId, fn($q, $v) => $q->where('medico_solicitante_id', $v));

        // Cantidad de meses/semanas del período, para los promedios
        if ($porRango) {
            $inicio = Carbon::parse($desde)->startOfDay();
            $fin    = Carbon::parse($hasta)->startOfDay();
        } else {
            $inicio = Carbon::create($anioSeleccionado, 1, 1)->startOfDay();
            $fin    = $anioSeleccionado === now()->year
                ? now()->startOfDay()
                : Carbon::create($anioSeleccionado, 12, 31)->startOfDay();
        }
        $dias        = max(1, (int) round($inicio->diffInDays($fin)) + 1);
        $total       = (clone $base)->count();
        $promedioMensual = round($total / max(1, $dias / 30.4375), 1);
        $promedioSemanal = round($total / max(1, $dias / 7), 1);

        // Estudios por mes
        $multiAnio = $porRango && Carbon::parse($desde)->year !== Carbon::parse($hasta)->year;
        $porMes = (clone $base)
            ->select(DB::raw("DATE_FORMAT(fecha, '%Y-%m') as ym"), DB::raw('COUNT(*) as total'))
            ->groupBy('ym')
            ->orderBy('ym')
            ->get()
            ->map(function ($item) use ($multiAnio) {
                $fecha = Carbon::createFromFormat('Y-m-d', $item->ym . '-01');
                $item->mes_nombre = ucfirst($fecha->translatedFormat($multiAnio ? 'M Y' : 'F'));
                return $item;
            });
        $porMesLabels  = $porMes->pluck('mes_nombre')->values();
        $porMesValores = $porMes->pluck('total')->values();

        // Top médicos solicitantes
        $porMedico = (clone $base)
            ->select('medico_solicitante_id', DB::raw('COUNT(*) as total'))
            ->groupBy('medico_solicitante_id')
            ->orderByDesc('total')
            ->with('medico_solicitante')
            ->get();
        $medicoLabels = $porMedico->take(5)->map(fn($i) => $i->medico_solicitante
            ? $i->medico_solicitante->apellido . ', ' . $i->medico_solicitante->nombre
            : 'Sin asignar')->values();
        $medicoValores = $porMedico->take(5)->pluck('total')->values();

        // Estudios reales (Rx de hombro, TC de abdomen, etc.), con regiones vistas
        $topEstudios = (clone $base)
            ->select(
                'estudio_id',
                DB::raw('COUNT(*) as total'),
                DB::raw('COALESCE(SUM(regiones), 0) as regiones_total'),
                DB::raw('MAX(tipo_estudio) as tipo_estudio_ref')
            )
            ->groupBy('estudio_id')
            ->orderByDesc('total')
            ->with('estudio.especialidad')
            ->get()
            ->map(function ($item) {
                $item->nombre = optional($item->estudio)->nombre ?? $item->tipo_estudio_ref ?? 'Sin especificar';
                return $item;
            });
        $estudioLabels  = $topEstudios->take(5)->pluck('nombre')->values();
        $estudioValores = $topEstudios->take(5)->pluck('total')->values();

        // Por modalidad (Rayos X / Tomografía) con sus estudios reales adentro
        $modalidades = $topEstudios
            ->groupBy(fn($i) => optional($i->estudio)->especialidad_id)
            ->map(function ($items) {
                return (object) [
                    'nombre'   => optional(optional($items->first()->estudio)->especialidad)->nombre ?? 'Sin modalidad',
                    'total'    => $items->sum('total'),
                    'estudios' => $items->sortByDesc('total')->values(),
                ];
            })
            ->sortByDesc('total')
            ->values();
        $modalidadLabels  = $modalidades->pluck('nombre')->values();
        $modalidadValores = $modalidades->pluck('total')->values();

        // Día de la semana (WEEKDAY: 0 = lunes ... 6 = domingo)
        $porDia = (clone $base)
            ->select(DB::raw('WEEKDAY(fecha) as dia'), DB::raw('COUNT(*) as total'))
            ->groupBy('dia')
            ->pluck('total', 'dia');
        $diaLabels  = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
        $diaValores = collect(range(0, 6))->map(fn($d) => (int) ($porDia[$d] ?? 0))->values();

        // Ambulatorios vs Internados
        $internados   = (clone $base)->where('ia', 'I')->count();
        $ambulatorios = (clone $base)->where('ia', 'A')->count();

        // Regiones e insumos de contraste
        $totalRegiones   = (int) (clone $base)->sum('regiones');
        $promedioRegiones = $total > 0 ? round($totalRegiones / $total, 1) : 0;
        $conContraste = (clone $base)->where(function ($q) {
            $q->where('cont_50ml', '>', 0)->orWhere('cont_100ml', '>', 0);
        })->count();
        $cont50   = (int) (clone $base)->sum('cont_50ml');
        $cont100  = (int) (clone $base)->sum('cont_100ml');
        $jeringas = (int) (clone $base)->sum('jeringa_prellenada');

        // Datos para los filtros
        $modalidadesDisponibles = Especialidad::modalidadesImagen()->orderBy('nombre')->get();
        $medicosDisponibles = Empleado::whereIn('id', EstudioMedico::select('medico_solicitante_id')->whereNotNull('medico_solicitante_id'))
            ->orderBy('apellido')->orderBy('nombre')->get();
        $servicioImagenId = Servicio::where('nombre', self::SERVICIO_DIAGNOSTICO_IMAGEN)->value('id');

        return view('estudios_medicos.estadisticas', compact(
            'total',
            'promedioMensual',
            'promedioSemanal',
            'porMes',
            'porMesLabels',
            'porMesValores',
            'porMedico',
            'medicoLabels',
            'medicoValores',
            'topEstudios',
            'estudioLabels',
            'estudioValores',
            'modalidades',
            'modalidadLabels',
            'modalidadValores',
            'diaLabels',
            'diaValores',
            'internados',
            'ambulatorios',
            'totalRegiones',
            'promedioRegiones',
            'conContraste',
            'cont50',
            'cont100',
            'jeringas',
            'aniosDisponibles',
            'anioSeleccionado',
            'modalidadesDisponibles',
            'medicosDisponibles',
            'modalidadId',
            'medicoId',
            'servicioImagenId'
        ));
    }

    // Stock actual (sumado) de los 3 insumos fijos, solo para mostrar "disponible: N" en el formulario
    private function stockDeInsumosFijos()
    {
        $servicio = Servicio::where('nombre', self::SERVICIO_DIAGNOSTICO_IMAGEN)->first();
        $nombres = [self::INSUMO_CONTRASTE_50, self::INSUMO_CONTRASTE_100, self::INSUMO_JERINGA];
        $resultado = [];

        foreach ($nombres as $nombre) {
            $medicamento = Medicamento::where('nombre', $nombre)->first();

            $disponible = 0;
            if ($medicamento && $servicio) {
                $disponible = Stock::where('medicamento_id', $medicamento->id)
                    ->where('servicio_id', $servicio->id)
                    ->where('cantidad_act', '>', 0)
                    ->sum('cantidad_act');
            }

            $resultado[$nombre] = (int) $disponible;
        }

        return $resultado;
    }

    // Busca (o crea) el Medicamento por nombre exacto, sin usar asignación masiva
    private function medicamentoPorNombre(string $nombre): Medicamento
    {
        $medicamento = Medicamento::where('nombre', $nombre)->first();

        if (!$medicamento) {
            $medicamento = new Medicamento();
            $medicamento->nombre = $nombre;
            $medicamento->save();
        }

        return $medicamento;
    }

    /**
     * Compara los insumos anteriores y nuevos para decidir si descuenta o devuelve stock.
     */
    private function procesarDeltaInsumo(EstudioMedico $estudioMedico, string $nombreMedicamento, string $campoColumna, int $nuevaCantidad): void
    {
        $cantidadOriginal = (int) $estudioMedico->$campoColumna;
        $diferencia = $nuevaCantidad - $cantidadOriginal;

        if ($diferencia > 0) {
            // El usuario incrementó los insumos. Descontamos la diferencia.
            $this->descontarInsumoFijo($estudioMedico, $nombreMedicamento, $diferencia);
        } elseif ($diferencia < 0) {
            // El usuario redujo los insumos. Devolvemos la diferencia.
            $this->devolverInsumoFijo($estudioMedico, $nombreMedicamento, abs($diferencia));
        }
    }

    // Descuenta "$cantidad" unidades del insumo fijo (por nombre de medicamento) del stock
    private function descontarInsumoFijo(EstudioMedico $estudioMedico, string $nombreMedicamento, int $cantidad): void
    {
        if ($cantidad <= 0) {
            return;
        }

        $servicio = Servicio::where('nombre', self::SERVICIO_DIAGNOSTICO_IMAGEN)->first();

        if (!$servicio) {
            throw new \Exception('No existe el servicio "Diagnóstico por Imágenes" en la tabla de servicios. Cargalo primero.');
        }

        $medicamento = $this->medicamentoPorNombre($nombreMedicamento);

        $lotes = Stock::where('medicamento_id', $medicamento->id)
            ->where('servicio_id', $servicio->id)
            ->where('cantidad_act', '>', 0)
            ->orderBy('fecha_vencimiento', 'asc')
            ->lockForUpdate()
            ->get();

        $disponibleTotal = $lotes->sum('cantidad_act');

        if ($disponibleTotal < $cantidad) {
            throw new \Exception("No hay stock suficiente de \"{$nombreMedicamento}\" en Diagnóstico por Imágenes (disponible: {$disponibleTotal}, necesitás: {$cantidad}).");
        }

        $restante = $cantidad;

        foreach ($lotes as $stock) {
            if ($restante <= 0) {
                break;
            }

            $aDescontar = min($restante, $stock->cantidad_act);
            $stock->decrement('cantidad_act', $aDescontar);
            $restante -= $aDescontar;

            Historial_stock::create([
                'stock_id'           => $stock->id,
                'estudio_medico_id'  => $estudioMedico->id,
                'cantidad'           => -$aDescontar,
                'fecha'              => now()->toDateString(),
                'empleado_id'        => $estudioMedico->medico_solicitante_id,
                'paciente_id'        => $estudioMedico->paciente_id,
                'comentario'         => "Consumido en estudio médico #{$estudioMedico->id} ({$nombreMedicamento})",
                'creado_por'         => auth()->id(),
            ]);
        }
    }

    /**
     * Devuelve "$cantidad" unidades de un insumo específico al stock (LIFO de devoluciones)
     */
    private function devolverInsumoFijo(EstudioMedico $estudioMedico, string $nombreMedicamento, int $cantidad): void
    {
        if ($cantidad <= 0) {
            return;
        }

        $medicamento = $this->medicamentoPorNombre($nombreMedicamento);

        // Buscamos los registros del historial de consumos de este estudio para este medicamento específico
        $historiales = Historial_stock::where('estudio_medico_id', $estudioMedico->id)
            ->where('cantidad', '<', 0) // Que sean salidas
            ->whereHas('get_stock', function ($query) use ($medicamento) {
                $query->where('medicamento_id', $medicamento->id);
            })
            ->orderBy('created_at', 'desc') // Devolvemos primero a los últimos lotes afectados
            ->get();

        $restante = $cantidad;

        foreach ($historiales as $movimiento) {
            if ($restante <= 0) {
                break;
            }

            $stock = Stock::find($movimiento->stock_id);
            if (!$stock) {
                continue;
            }

            $consumidoOriginalmente = abs($movimiento->cantidad);
            $aDevolver = min($restante, $consumidoOriginalmente);

            // Devolvemos la cantidad al lote de stock
            $stock->increment('cantidad_act', $aDevolver);
            $restante -= $aDevolver;

            // Registramos la devolución en el historial
            Historial_stock::create([
                'stock_id'           => $stock->id,
                'estudio_medico_id'  => $estudioMedico->id,
                'cantidad'           => $aDevolver, // Positivo para denotar reingreso
                'fecha'              => now()->toDateString(),
                'empleado_id'        => $estudioMedico->medico_solicitante_id,
                'paciente_id'        => $estudioMedico->paciente_id,
                'comentario'         => "Devolución automática (Edición) por estudio médico #{$estudioMedico->id} ({$nombreMedicamento})",
                'creado_por'         => auth()->id(),
            ]);
        }

        // Caso de seguridad: Si no se encontró el historial original o se eliminaron lotes antiguos,
        // devolvemos las unidades al lote vigente más conveniente.
        if ($restante > 0) {
            $servicio = Servicio::where('nombre', self::SERVICIO_DIAGNOSTICO_IMAGEN)->first();
            $loteVigente = Stock::where('medicamento_id', $medicamento->id)
                ->where('servicio_id', $servicio->id)
                ->orderBy('fecha_vencimiento', 'desc')
                ->first();

            if ($loteVigente) {
                $loteVigente->increment('cantidad_act', $restante);

                Historial_stock::create([
                    'stock_id'           => $loteVigente->id,
                    'estudio_medico_id'  => $estudioMedico->id,
                    'cantidad'           => $restante,
                    'fecha'              => now()->toDateString(),
                    'empleado_id'        => $estudioMedico->medico_solicitante_id,
                    'paciente_id'        => $estudioMedico->paciente_id,
                    'comentario'         => "Devolución excedente (Lote no encontrado) por estudio médico #{$estudioMedico->id}",
                    'creado_por'         => auth()->id(),
                ]);
            }
        }
    }
}
