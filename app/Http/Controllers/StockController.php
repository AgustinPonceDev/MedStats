<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Historial_stock;
use App\Models\Paciente;
use App\Models\Empleado;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Servicio;

class StockController extends Controller
{
    // Ventana de análisis para la proyección de consumo (en días)
    private const VENTANA_PROYECCION = 30;

    // Umbrales de días restantes para clasificar la urgencia de reposición
    private const DIAS_CRITICO = 7;
    private const DIAS_AVISO = 15;

    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Stock::with(['get_medicamento', 'get_servicio']);
        $servicioRestringido = $user->servicioRestringido();

        if ($servicioRestringido) {
            $query->where('servicio_id', $servicioRestringido);
            $servicios = Servicio::where('id', $servicioRestringido)->get();
        } else {
            if ($request->filled('servicio_id')) {
                $query->where('servicio_id', $request->servicio_id);
            }
            $servicios = Servicio::all();
        }

        $stock = $query->get();

        // Adjuntamos la proyección de consumo a cada insumo para mostrar el badge
        // en el listado (no es una columna de la BD, se calcula al vuelo).
        $stock->each(function ($item) {
            $item->proyeccion = $this->calcularProyeccion($item);
        });

        return view('stocks.index', compact('stock', 'servicios', 'servicioRestringido'));
    }

    public function create()
    {
        $medicamentos = Medicamento::pluck('nombre', 'id');

        $user = auth()->user();
        $servicioRestringido = $user->servicioRestringido();

        if ($servicioRestringido) {
            $servicios = Servicio::where('id', $servicioRestringido)->get();
        } else {
            $servicios = Servicio::all();
        }

        return view('stocks.create', compact('medicamentos', 'servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicamento_id' => 'required|integer|exists:medicamentos,id',
            'lote' => 'required|string|max:50',
            'fecha_vencimiento' => 'required|date',
            'cantidad_act' => 'required|integer|min:0',
            'umbral_aviso' => 'nullable|integer|min:0',
            'umbral_critico' => 'nullable|integer|min:0|lte:umbral_aviso',
        ], [
            'medicamento_id.required' => 'Tenés que elegir un medicamento del listado (no escribirlo libre).',
            'medicamento_id.exists' => 'Ese medicamento no existe. Elegilo del listado desplegable.',
            'umbral_critico.lte' => 'El umbral crítico tiene que ser menor o igual que el de aviso.',
        ]);

        $user = auth()->user();
        $servicioRestringido = $user->servicioRestringido();
        $inputServicio = $request->input('servicio_id');

        // Si el usuario (o su perfil) está restringido a un servicio, se lo forzamos
        if ($servicioRestringido) {
            $inputServicio = $servicioRestringido;
        } else {
            $request->validate(['servicio_id' => 'required|exists:servicios,id']);
        }

        $existe = Stock::where('medicamento_id', $request->input('medicamento_id'))
        ->where('lote', $request->input('lote'))
        ->where('servicio_id', $inputServicio)
        ->exists();

        if ($existe) {
            return redirect()->back()
            ->withErrors(['lote' => 'Ya existe un stock para este medicamento con ese lote en este servicio.'])
            ->withInput();
        }

        $stock = new Stock();
        $stock->medicamento_id = $request->input('medicamento_id');
        $stock->fecha_vencimiento = $request->input('fecha_vencimiento');
        $stock->lote = $request->input('lote');
        $stock->cantidad_act = $request->input('cantidad_act');
        $stock->servicio_id = $inputServicio;
        // Umbrales de aviso/crítico definidos por quien carga el insumo (con default 50/30 si no se cargan)
        $stock->umbral_aviso = $request->filled('umbral_aviso') ? $request->input('umbral_aviso') : 50;
        $stock->umbral_critico = $request->filled('umbral_critico') ? $request->input('umbral_critico') : 30;
        $stock->save();

        Historial_stock::create([
            'stock_id' => $stock->id,
            'cantidad' => $request->input('cantidad_act'),
            'fecha' => now()->toDateString(),
            'comentario' => 'Carga inicial de stock',
            'empleado_id' => null,
            'paciente_id' => null,
            'creado_por' => auth()->id(),
        ]);
        return redirect()->route('stocks.index')->with('success', 'Medicamento cargado con éxito');
    }

    public function show(Stock $stock)
    {
        $hist_item = Historial_stock::where('stock_id', $stock->id)
            ->paginate(15);
        return view('stocks.show', compact('hist_item', 'stock'));
    }
    public function edit(Stock $stock, Request $request)
    {
        $modo = $request->query('modo'); // puede ser 'agregar' o 'extraer'
        $pacientes = Paciente::all();
        $empleados = Empleado::all();

        return view('stocks.edit', compact('stock', 'pacientes', 'empleados', 'modo'));
    }
    
    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'medicamento_id' => 'required|exists:medicamentos,id',
            'cantidad_agregar' => 'nullable|integer|min:0',
            'cantidad_extraer' => 'nullable|integer|min:0',
            'umbral_aviso' => 'nullable|integer|min:0',
            'umbral_critico' => 'nullable|integer|min:0|lte:umbral_aviso',
        ], [
            'umbral_critico.lte' => 'El umbral crítico tiene que ser menor o igual que el de aviso.',
        ]);

        $existe = Stock::where('medicamento_id', $request->input('medicamento_id'))
            ->where('lote', $stock->lote)
            ->where('servicio_id', $stock->servicio_id)
            ->where('id', '!=', $stock->id)
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->withErrors(['lote' => 'Ya existe un stock para este medicamento con ese lote.'])
                ->withInput();
        }

        $oldCantidad = $stock->cantidad_act;
        $agregar = $request->input('cantidad_agregar', 0);
        $extraer = $request->input('cantidad_extraer', 0);

        if ($agregar > 0 && $extraer > 0) {
            return redirect()->back()
                ->withErrors(['cantidad_agregar' => 'Solo se puede agregar o extraer, no ambas acciones a la vez.'])
                ->withInput();
        }

        if ($agregar === 0 && $extraer === 0 && !$request->filled('umbral_aviso') && !$request->filled('umbral_critico')) {
            return redirect()->route('stocks.index');
        }

        $nuevaCantidad = $oldCantidad + $agregar - $extraer;
        if ($nuevaCantidad < 0) {
            return redirect()->back()
                ->withErrors(['cantidad_extraer' => 'No se puede descontar más de lo que hay en stock.'])
                ->withInput();
        }

        if ($extraer > 0) {
            $request->validate([
                'paciente_id' => 'required|exists:pacientes,id',
                'empleado_id' => 'required|exists:empleados,id',
                'comentario' => 'nullable|string|max:255',
            ]);
        }

        $stock->medicamento_id = $request->input('medicamento_id');
        $stock->fecha_vencimiento = $request->filled('fecha_vencimiento')
            ? $request->input('fecha_vencimiento')
            : $stock->fecha_vencimiento;
        $stock->cantidad_act = $nuevaCantidad;
        if ($request->filled('umbral_aviso')) {
            $stock->umbral_aviso = $request->input('umbral_aviso');
        }
        if ($request->filled('umbral_critico')) {
            $stock->umbral_critico = $request->input('umbral_critico');
        }
        $stock->save();

        $cantidad_modificada = $agregar > 0 ? $agregar : -$extraer;
        $comentario = $request->input('comentario') ?? ($agregar > 0 ? 'Se aumentó el stock.' : 'Se descontó stock.');

        if ($cantidad_modificada !== 0) {
            Historial_stock::create([
                'stock_id' => $stock->id,
                'cantidad' => $cantidad_modificada,
                'fecha' => now()->toDateString(),
                'empleado_id' => $request->input('empleado_id'),
                'paciente_id' => $request->input('paciente_id'),
                'comentario' => $comentario,
                'creado_por' => auth()->id(),
            ]);
        }
        return redirect()->route('stocks.index');
    }

    public function estadisticas(Request $request)
    {
        $validated = $request->validate([
            'desde' => 'nullable|date|before_or_equal:today',
            'hasta' => 'nullable|date|after_or_equal:desde|before_or_equal:today',
            'servicio_id' => 'nullable|exists:servicios,id',
        ], [
            'desde.date' => 'La fecha de inicio no tiene un formato válido.',
            'desde.before_or_equal' => 'La fecha de inicio no puede ser futura.',
            'hasta.date' => 'La fecha de fin no tiene un formato válido.',
            'hasta.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'hasta.before_or_equal' => 'La fecha de fin no puede ser futura.',
        ]);

        $desde = $validated['desde'] ?? now()->startOfMonth()->toDateString();
        $hasta = $validated['hasta'] ?? now()->endOfMonth()->toDateString();

        $umbralDias = max(1, intval($request->input('dias', 30)));
        $fechaLimite = now()->subDays($umbralDias)->toDateString();

        $user = auth()->user();
        $servicioRestringido = $user->servicioRestringido();
        $servicioId = $servicioRestringido ?: $request->input('servicio_id');

        if ($servicioRestringido) {
            $servicios = Servicio::where('id', $servicioRestringido)->get();
        } else {
            $servicios = Servicio::all();
        }

        $stocksQuery = Stock::query()->when($servicioId, fn ($q) => $q->where('servicio_id', $servicioId));
        $stockIdsFiltrados = (clone $stocksQuery)->pluck('id');

        // ---------- CÁLCULO DEL TOTAL DE INSUMOS AL CIERRE DE 'HASTA' ----------
        $movimientosPosteriores = Historial_stock::select('stock_id', DB::raw('SUM(cantidad) as suma_posterior'))
            ->whereIn('stock_id', $stockIdsFiltrados)
            ->where('fecha', '>', $hasta)
            ->groupBy('stock_id')
            ->get()
            ->keyBy('stock_id');

        $stocks = $stocksQuery->get();
        $totalStockAlCierre = 0;
        foreach ($stocks as $s) {
            $sumaPosterior = $movimientosPosteriores->has($s->id) ? $movimientosPosteriores[$s->id]->suma_posterior : 0;
            $stockAlCierre = $s->cantidad_act - $sumaPosterior;
            $totalStockAlCierre += max(0, $stockAlCierre);
        }
        $totalStock = $totalStockAlCierre;
        // -----------------------------------------------------------------------

        $movimientosNetosPeriodo = Historial_stock::select('stock_id', DB::raw('SUM(cantidad) as neto'))
            ->whereIn('stock_id', $stockIdsFiltrados)
            ->whereBetween('fecha', [$desde, $hasta])
            ->groupBy('stock_id')
            ->with('get_stock.get_medicamento')
            ->get();

        $totalAgregados = $movimientosNetosPeriodo->filter(fn ($m) => $m->neto > 0)->sum('neto');

        $totalExtraidos = $movimientosNetosPeriodo->filter(fn ($m) => $m->neto < 0)
            ->sum(fn ($m) => abs($m->neto));

        $insumos = $movimientosNetosPeriodo
            ->filter(fn ($m) => $m->neto < 0)
            ->map(function ($m) {
                $m->total = abs($m->neto);
                return $m;
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();

        $insumoLabels = $insumos->map(fn($item) =>
            optional($item->get_stock->get_medicamento)->nombre ?? 'Sin nombre'
        );

        $insumoValores = $insumos->pluck('total');

        $vencimientos = (clone $stocksQuery)
            ->whereNotNull('fecha_vencimiento')
            ->whereBetween('fecha_vencimiento', [now(), now()->addDays(60)])
            ->with('get_medicamento')
            ->orderBy('fecha_vencimiento')
            ->get();

        $stocksSinMovimiento = (clone $stocksQuery)
            ->whereDoesntHave('historial_stock', function ($query) use ($fechaLimite) {
                $query->where('fecha', '>', $fechaLimite);
            })->with('get_medicamento')->get();

        // ---------- PROYECCIÓN PROFESIONAL DE CONSUMO ----------
        $proyecciones = (clone $stocksQuery)->with('get_medicamento')->get()->map(function ($stock) {
            $p = $this->calcularProyeccion($stock);

            return [
                'medicamento' => optional($stock->get_medicamento)->nombre,
                'lote' => $stock->lote,
                'cantidad_act' => $stock->cantidad_act,
                'consumo_diario_simple' => $p['consumo_diario_simple'],
                'consumo_diario' => $p['consumo_diario_ponderado'],
                'tendencia' => $p['tendencia'],
                'dias_restantes' => $p['dias_restantes'],
                'urgencia' => $p['urgencia'],
            ];
        });

        return view('stocks.estadisticasstock', compact(
            'totalStock',
            'totalAgregados',
            'totalExtraidos',
            'insumoLabels',
            'insumoValores',
            'vencimientos',
            'desde',
            'hasta',
            'stocksSinMovimiento',
            'umbralDias',
            'proyecciones',
            'servicios',
            'servicioId',
            'servicioRestringido'
        ));
    }

    /**
     * Proyección profesional de consumo para un lote puntual, combinando:
     *  - Promedio simple: consumo total / días de la ventana (línea base, estable)
     *  - Promedio ponderado: los días más recientes pesan más
     *  - Tendencia: pendiente de una regresión lineal sobre el consumo diario
     *
     * Simula día a día el agotamiento del lote partiendo del consumo ponderado
     * y ajustándolo según la tendencia detectada.
     */
    private function calcularProyeccion(Stock $stock, int $ventanaDias = self::VENTANA_PROYECCION): array
    {
        $hoy = now()->startOfDay();
        $fechaInicio = $hoy->copy()->subDays($ventanaDias - 1);

        $movimientosPorDia = Historial_stock::where('stock_id', $stock->id)
            ->whereBetween('fecha', [$fechaInicio->toDateString(), $hoy->toDateString() . ' 23:59:59'])
            ->select(DB::raw('DATE(fecha) as fecha_dia'), DB::raw('SUM(cantidad) as neto'))
            ->groupBy('fecha_dia')
            ->pluck('neto', 'fecha_dia');

        $serie = [];
        for ($i = 0; $i < $ventanaDias; $i++) {
            $fecha = $fechaInicio->copy()->addDays($i)->toDateString();
            $neto = $movimientosPorDia[$fecha] ?? 0;
            $serie[] = $neto < 0 ? abs($neto) : 0.0;
        }

        $n = count($serie);
        $consumoTotal = array_sum($serie);

        if ($consumoTotal <= 0) {
            return [
                'consumo_diario_simple' => 0.0,
                'consumo_diario_ponderado' => 0.0,
                'tendencia' => 'estable',
                'dias_restantes' => null,
                'urgencia' => 'sin_datos',
            ];
        }

        $consumoSimple = $consumoTotal / $n;

        $sumaPesos = 0;
        $sumaPonderada = 0;
        foreach ($serie as $i => $valor) {
            $peso = $i + 1;
            $sumaPesos += $peso;
            $sumaPonderada += $valor * $peso;
        }
        $consumoPonderado = $sumaPesos > 0 ? $sumaPonderada / $sumaPesos : $consumoSimple;

        $sumaX = 0;
        $sumaY = 0;
        $sumaXY = 0;
        $sumaXX = 0;
        foreach ($serie as $x => $y) {
            $sumaX += $x;
            $sumaY += $y;
            $sumaXY += $x * $y;
            $sumaXX += $x * $x;
        }
        $denominador = ($n * $sumaXX) - ($sumaX * $sumaX);
        $pendiente = $denominador != 0 ? (($n * $sumaXY) - ($sumaX * $sumaY)) / $denominador : 0.0;

        $umbralPendiente = max(0.05, $consumoPonderado * 0.05);
        if ($pendiente > $umbralPendiente) {
            $tendencia = 'creciente';
        } elseif ($pendiente < -$umbralPendiente) {
            $tendencia = 'decreciente';
        } else {
            $tendencia = 'estable';
        }

        $stockSimulado = (float) $stock->cantidad_act;
        $consumoDiarioSim = $consumoPonderado;
        $dias = 0;
        $maxDias = 365;
        $diasRestantes = null;

        while ($dias < $maxDias) {
            if ($consumoDiarioSim <= 0) {
                break;
            }

            $stockSimulado -= $consumoDiarioSim;
            $dias++;

            if ($stockSimulado <= 0) {
                $diasRestantes = $dias;
                break;
            }

            $consumoDiarioSim = max(0, $consumoDiarioSim + $pendiente);
        }

        if ($diasRestantes === null) {
            $urgencia = 'ok';
        } elseif ($diasRestantes <= self::DIAS_CRITICO) {
            $urgencia = 'critico';
        } elseif ($diasRestantes <= self::DIAS_AVISO) {
            $urgencia = 'aviso';
        } else {
            $urgencia = 'ok';
        }

        return [
            'consumo_diario_simple' => round($consumoSimple, 2),
            'consumo_diario_ponderado' => round($consumoPonderado, 2),
            'tendencia' => $tendencia,
            'dias_restantes' => $diasRestantes,
            'urgencia' => $urgencia,
        ];
    }
}
