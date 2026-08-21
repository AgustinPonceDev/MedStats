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

        DB::beginTransaction();
        try {
            $estudioMedico = EstudioMedico::create($data);

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
            'especialidad_id'       => ['required', 'integer', \Illuminate\Validation\Rule::exists('especialidads', 'id')->where(fn ($q) => $q->where('es_modalidad_imagen', true))],
            'estudio_id'            => ['required', 'integer', \Illuminate\Validation\Rule::exists('estudios', 'id')->where(fn ($q) => $q->where('especialidad_id', $request->input('especialidad_id')))],
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
            ->whereHas('get_stock', function($query) use ($medicamento) {
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