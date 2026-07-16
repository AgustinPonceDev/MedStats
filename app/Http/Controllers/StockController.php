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
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Stock::with(['get_medicamento', 'get_servicio']);

        // Check if user is restricted to a service
        if ($user->servicio_id) {
            $query->where('servicio_id', $user->servicio_id);
            // Limit available filters
            $servicios = Servicio::where('id', $user->servicio_id)->get();
        } else {
             // Admin/Global: Show all or filter by request
             if ($request->filled('servicio_id')) {
                $query->where('servicio_id', $request->servicio_id);
            }
            $servicios = Servicio::all();
        }

        $stock = $query->get();
        return view('stocks.index', compact('stock', 'servicios'));
    }

    public function create()
    {
        $medicamentos = Medicamento::pluck('nombre', 'id');
        
        $user = auth()->user();
        if ($user->servicio_id) {
            $servicios = Servicio::where('id', $user->servicio_id)->get();
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
        $inputServicio = $request->input('servicio_id');
        
        // If user is restricted, force their service ID
        if ($user->servicio_id) {
            $inputServicio = $user->servicio_id;
        } else {
            // If explicit input is missing for admin, validation fails
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
        $modo = $request->query('modo');
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
        $servicioId = $user->servicio_id ?: $request->input('servicio_id');

        if ($user->servicio_id) {
            $servicios = \App\Models\Servicio::where('id', $user->servicio_id)->get();
        } else {
            $servicios = \App\Models\Servicio::all();
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

        // ---------- NUEVO CÁLCULO DE INGRESOS (TOTAL AGREGADOS) ----------
        // Se consideran ingresos las cargas iniciales y reposiciones manuales positivas.
        // Se excluyen explícitamente las devoluciones de estudios (que tienen estudio_medico_id).
        $totalAgregados = Historial_stock::whereIn('stock_id', $stockIdsFiltrados)
            ->where('cantidad', '>', 0)
            ->whereNull('estudio_medico_id')
            ->whereBetween('fecha', [$desde, $hasta])
            ->sum('cantidad');

        // ---------- NUEVO CÁLCULO DE CONSUMOS (NETO DE EXTRAÍDOS) ----------
        // Para calcular el consumo real (utilizados), sumamos los consumos (negativos) 
        // y le sumamos las devoluciones (positivos que tengan estudio_medico_id).
        // Matemáticamente: SUM(cantidad de salidas) + SUM(cantidad de devoluciones de estudio)
        $salidasYDevoluciones = Historial_stock::whereIn('stock_id', $stockIdsFiltrados)
            ->whereBetween('fecha', [$desde, $hasta])
            ->where(function($query) {
                $query->where('cantidad', '<', 0)
                      ->orWhere(function($q) {
                          $q->where('cantidad', '>', 0)
                            ->whereNotNull('estudio_medico_id');
                      });
            })
            ->sum('cantidad');

        // Como las salidas son negativas y las devoluciones positivas, el resultado neto es negativo. 
        // Aplicamos valor absoluto para mostrarlo positivo en los indicadores.
        $totalExtraidos = abs($salidasYDevoluciones);

        // ---------- INSUMOS MÁS UTILIZADOS EN EL PERÍODO (NETO) ----------
        // Agrupamos y calculamos el neto consumido por cada stock_id para reflejar fielmente el gráfico.
        $insumosConsumoNeto = Historial_stock::select(
                'stock_id', 
                DB::raw('ABS(SUM(cantidad)) as total_neto')
            )
            ->whereIn('stock_id', $stockIdsFiltrados)
            ->whereBetween('fecha', [$desde, $hasta])
            ->where(function($query) {
                $query->where('cantidad', '<', 0)
                      ->orWhere(function($q) {
                          $q->where('cantidad', '>', 0)
                            ->whereNotNull('estudio_medico_id');
                      });
            })
            ->groupBy('stock_id')
            // Filtrar para que solo muestre los que realmente tuvieron un saldo de consumo neto
            ->having('total_neto', '>', 0) 
            ->with('get_stock.get_medicamento')
            ->orderByDesc('total_neto')
            ->take(5)
            ->get();

        $insumoLabels = $insumosConsumoNeto->map(fn($item) =>
            optional($item->get_stock->get_medicamento)->nombre ?? 'Sin nombre'
        );

        $insumoValores = $insumosConsumoNeto->pluck('total_neto');

        // Vencimientos próximos (dentro de 60 días)
        $vencimientos = (clone $stocksQuery)
            ->whereNotNull('fecha_vencimiento')
            ->whereBetween('fecha_vencimiento', [now(), now()->addDays(60)])
            ->with('get_medicamento')
            ->orderBy('fecha_vencimiento')
            ->get();

        // Insumos sin movimiento según umbralDias
        $stocksSinMovimiento = (clone $stocksQuery)
            ->whereDoesntHave('historial_stock', function ($query) use ($fechaLimite) {
                $query->where('fecha', '>', $fechaLimite);
            })->with('get_medicamento')->get();

        // Proyección de duración de stock (últimos 30 días) basándonos también en el neto diario
        $periodoAnalisis = 30;
        $fechaInicio = now()->subDays($periodoAnalisis)->toDateString();
        $fechaFin = now()->toDateString();

        $consumos = Historial_stock::join('stocks', 'historial_stocks.stock_id', '=', 'stocks.id')
            ->select('stocks.medicamento_id', DB::raw('ABS(SUM(historial_stocks.cantidad)) as total_consumo_neto'))
            ->whereIn('historial_stocks.stock_id', $stockIdsFiltrados)
            ->whereBetween('historial_stocks.fecha', [$fechaInicio, $fechaFin])
            ->where(function($query) {
                $query->where('historial_stocks.cantidad', '<', 0)
                      ->orWhere(function($q) {
                          $q->where('historial_stocks.cantidad', '>', 0)
                            ->whereNotNull('historial_stocks.estudio_medico_id');
                      });
            })
            ->groupBy('stocks.medicamento_id')
            ->get()
            ->keyBy('medicamento_id');

        $proyecciones = (clone $stocksQuery)->with('get_medicamento')->get()->map(function ($stock) use ($consumos, $periodoAnalisis) {
            $consumoTotal = $consumos[$stock->medicamento_id]->total_consumo_neto ?? 0;
            $consumoDiario = $consumoTotal / $periodoAnalisis;
            $diasRestantes = $consumoDiario > 0 ? round($stock->cantidad_act / $consumoDiario) : null;

            return [
                'medicamento' => optional($stock->get_medicamento)->nombre,
                'lote' => $stock->lote,
                'cantidad_act' => $stock->cantidad_act,
                'consumo_diario' => round($consumoDiario, 2),
                'dias_restantes' => $diasRestantes,
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
            'servicioId'
        ));
    }
}