<?php

namespace App\Http\Controllers;

use App\Models\Cirugia;
use App\Models\Paciente;
use App\Models\Empleado;
use App\Models\Especialidad;
use App\Models\Procedimiento;
use App\Models\Quirofano;
use App\Models\Tipo_anestesia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class CirugiaController extends Controller
{

    //Muestra todos los datos
    public function index() //Pagina inicial
    {
        $cirugias = Cirugia::with([
            'get_paciente',
            'get_especialidad',
            'get_procedimiento',
            'get_quirofano',
            'get_cirujano',
            'get_ayudante1',
            'get_ayudante2',
            'get_ayudante3',
            'get_anestesista',
            'get_instrumentador',
            'get_instrumentador2',
            'get_enfermero',
            'get_enfermero2',
            'get_tipo_anestesia',
            'get_tipo_anestesia2',
            'modificador',
        ])->orderBy('id', 'desc')->get();
        return view('cirugias.index', compact('cirugias')); //Llama a la vista y le pasa las Cirugias obtenidas
    }

    public function show(Cirugia $cirugia)
    {
        return view('cirugias.show', compact('cirugia'));
    }
    public function create()
    {
        $pacientes = Paciente::orderBy('apellido')->orderBy('nombre')->get();
        $empleados = Empleado::orderBy('apellido')->orderBy('nombre')->get();
        $especialidades = Especialidad::quirurgicas()->orderBy('nombre')->get();
        $procedimientos = Procedimiento::orderBy('nombre_procedimiento')->get();
        $quirofanos = Quirofano::orderBy('nombre')->get();
        $tipoAnestesias = Tipo_anestesia::orderBy('nombre')->get();
        return view('cirugias.create', compact('pacientes', 'empleados', 'especialidades', 'procedimientos', 'quirofanos', 'tipoAnestesias'));
    }


    public function store(Request $request)
    {
        // try{
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'especialidad_id' => [
                'required',
                Rule::exists('especialidads', 'id')->where(fn($q) => $q->where('es_modalidad_imagen', false)),
            ],
            'procedimiento_id' => 'required|exists:procedimientos,id',
            'quirofano_id' => 'required|exists:quirofanos,id',
            'cirujano_id' => 'required|exists:empleados,id',
            'anestesista_id' => 'nullable|exists:empleados,id',
            'tipo_anestesia_id' => 'required|exists:tipo_anestesias,id',
            'instrumentador_id' => 'nullable|exists:empleados,id',
            'enfermero_id' => 'required|exists:empleados,id',
            'fecha_cirugia' => 'required',
            'hora_cirugia' => 'required',
            'duracion_horas' => 'nullable|integer|min:0',
            'duracion_minutos' => 'nullable|integer|min:0|max:59',
            'suspendida' => 'nullable|boolean',
            'observacion_suspension' => 'nullable|string',
        ], [
            'paciente_id.required' => 'Seleccioná un paciente antes de continuar.',
            'paciente_id.exists' => 'El paciente seleccionado no existe en el sistema.',

            'especialidad_id.required' => 'Indicá la especialidad a realizar.',
            'especialidad_id.exists' => 'La especialidad seleccionada no es válida para cirugías.',

            'procedimiento_id.required' => 'Indicá el procedimiento a realizar.',
            'procedimiento_id.exists' => 'El procedimiento no está registrado.',

            'quirofano_id.required' => 'Seleccioná el quirófano asignado.',
            'quirofano_id.exists' => 'Ese quirófano no está disponible o no existe.',

            'cirujano_id.required' => 'Asigná un cirujano para la cirugía.',
            'cirujano_id.exists' => 'El cirujano seleccionado no está registrado.',

            'anestesista_id.required' => 'Asigná un anestesiologo para el procedimiento.',
            'anestesista_id.exists' => 'El anestesiologo seleccionado no está registrado.',

            'tipo_anestesia_id.required' => 'Indicá el tipo de anestesia.',
            'tipo_anestesia_id.exists' => 'Ese tipo de anestesia no está registrado.',

            'instrumentador_id.exists' => 'El instrumentador seleccionado no está registrado.',

            'enfermero_id.required' => 'Asigná un enfermero/a para la cirugía.',
            'enfermero_id.exists' => 'El enfermero/a seleccionado no está registrado.',

            'fecha_cirugia.required' => 'Indicá la fecha programada para la cirugía.',
            'hora_cirugia.required' => 'Indicá la hora programada para la cirugía.',
        ]);
        if ($request->input('ayudante_1_id') != null) {
            $request->validate([
                'ayudante_1_id' => 'exists:empleados,id|nullable|different:cirujano_id',
            ], [
                'ayudante_1_id.different' => 'El ayudante 1 debe ser distinto a los demas.',
            ]);
        }
        if ($request->input('ayudante_2_id') != null) {
            $request->validate([
                'ayudante_2_id' => 'exists:empleados,id|nullable|different:ayudante_1_id',
            ], [
                'ayudante_2_id.different' => 'El ayudante 2 debe ser distinto a los demas.',
            ]);
        }
        if ($request->input('ayudante_3_id') != null) {
            $request->validate([
                'ayudante_3_id' => 'exists:empleados,id|nullable|different:ayudante_1_id|different:ayudante_2_id',
            ], [
                'ayudante_3_id.different' => 'El ayudante 3 debe ser distinto a los demas.',
            ]);
        }
        if ($request->input('procedimiento_2_id') != null) {
            $request->validate([
                'procedimiento_2_id' => 'exists:procedimientos,id|nullable|different:procedimiento_id',
            ], [
                'procedimiento_2_id.different' => 'El Procedimiento 2 debe ser distinto a Procedimiento.',
            ]);
        }
        if ($request->input('instrumentador_2_id') != null) {
            $request->validate([
                'instrumentador_2_id' => 'exists:empleados,id|nullable|different:instrumentador_id',
            ], [
                'instrumentador_2_id.different' => 'El Instrumentador 2 debe ser distinto a Insturmentador.',
            ]);
        }
        if ($request->input('enfermero_2_id') != null) {
            $request->validate([
                'enfermero_2_id' => 'exists:empleados,id|nullable|different:enfermero_id',
            ], [
                'enfermero_2_id.different' => 'El Enfermero 2 debe ser distinto a Enfermero.',
            ]);
        }
        if ($request->input('tipo_anestesia_2_id') != null) {
            $request->validate([
                'tipo_anestesia_2_id' => 'exists:tipo_anestesias,id|nullable|different:tipo_anestesia_id',
            ], [
                'tipo_anestesia_2_id.different' => 'El tipo de anestesia 2 debe ser distinto a tipo de anestesia.',
            ]);
        }
        // } catch(\Illuminate\Validation\ValidationException $e){
        //     dd($e->errors());
        // }
        $cirugia = new Cirugia();
        //Datos del POST se obtiene en request
        $cirugia->paciente_id = $request->input('paciente_id');
        $cirugia->especialidad_id = $request->input('especialidad_id');
        $cirugia->procedimiento_id = $request->input('procedimiento_id');
        $cirugia->procedimiento_2_id = $request->input('procedimiento_2_id');
        $cirugia->quirofano_id = $request->input('quirofano_id');
        $cirugia->cirujano_id = $request->input('cirujano_id');
        $cirugia->ayudante_1_id = $request->input('ayudante_1_id');
        $cirugia->ayudante_2_id = $request->input('ayudante_2_id');
        $cirugia->ayudante_3_id = $request->input('ayudante_3_id');
        $cirugia->anestesista_id = $request->input('anestesista_id');
        $cirugia->tipo_anestesia_id = $request->input('tipo_anestesia_id');
        $cirugia->tipo_anestesia_2_id = $request->input('tipo_anestesia_2_id');
        $cirugia->instrumentador_id = $request->input('instrumentador_id');
        $cirugia->instrumentador_2_id = $request->input('instrumentador_2_id');
        $cirugia->enfermero_id = $request->input('enfermero_id');
        $cirugia->enfermero_2_id = $request->input('enfermero_2_id');
        $cirugia->fecha_cirugia = $request->input('fecha_cirugia');
        $cirugia->hora_cirugia = $request->input('hora_cirugia');

        //Formatear duración
        $horas = $request->input('duracion_horas', 0);
        $minutos = $request->input('duracion_minutos', 0);
        $duracion = sprintf('%02d:%02d', $horas, $minutos);
        $cirugia->duracion = $duracion;
        $cirugia->creado_por = auth()->id();
        $cirugia->modificado_por = auth()->id();

        if ($request->input('urgencia') != null) {
            $cirugia->urgencia = true;
        } else {
            $cirugia->urgencia = false;
        }
        if ($request->input('obito') != null) {
            $cirugia->obito = true;
        } else {
            $cirugia->obito = false;
        }
        $cirugia->suspendida = $request->has('suspendida');
        $cirugia->observacion_suspension = $cirugia->suspendida ? $request->input('observacion_suspension') : null;

        // dd($cirugia);
        $cirugia->save(); //Guarda en la BD, si existe lo actualiza, sino crea
        if ($request->input('action') === 'cargar_medicamentos') {
            return redirect()->route('cirugias.medicamentos', $cirugia);
        }
        return redirect()->route('cirugias.index');
    }

    public function edit(Cirugia $cirugia)
    {
        $pacientes = Paciente::orderBy('apellido')->orderBy('nombre')->get();
        $empleados = Empleado::with('get_profesion')->orderBy('apellido')->orderBy('nombre')->get();
        $especialidades = Especialidad::quirurgicas()->orderBy('nombre')->get();

        // Si la cirugía ya tenía cargada una especialidad de Diagnóstico por Imágenes
        // (dato previo al toggle), la agregamos igual para no dejar el select vacío.
        if ($cirugia->especialidad_id && !$especialidades->contains('id', $cirugia->especialidad_id)) {
            $especialidadActual = Especialidad::find($cirugia->especialidad_id);
            if ($especialidadActual) {
                $especialidades->push($especialidadActual);
            }
        }

        $procedimientos = Procedimiento::with('get_especialidad')->orderBy('nombre_procedimiento')->get();
        $quirofanos = Quirofano::orderBy('nombre')->get();
        $tipoAnestesias = Tipo_anestesia::orderBy('nombre')->get();
        return view('cirugias.edit', compact('cirugia', 'pacientes', 'empleados', 'especialidades', 'procedimientos', 'quirofanos', 'tipoAnestesias'));
    }

    public function update(Request $request, Cirugia $cirugia)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'especialidad_id' => [
                'required',
                Rule::exists('especialidads', 'id')->where(function ($q) use ($cirugia) {
                    $q->where('es_modalidad_imagen', false)
                        ->orWhere('id', $cirugia->especialidad_id);
                }),
            ],
            'procedimiento_id' => 'required|exists:procedimientos,id',
            'quirofano_id' => 'required|exists:quirofanos,id',
            'cirujano_id' => 'required|exists:empleados,id',
            'anestesista_id' => 'nullable|exists:empleados,id',
            'tipo_anestesia_id' => 'nullable|exists:tipo_anestesias,id',
            'instrumentador_id' => 'nullable|exists:empleados,id',
            'enfermero_id' => 'nullable|exists:empleados,id',
            'fecha_cirugia' => 'required',
            'hora_cirugia' => 'required',
            'duracion_horas' => 'nullable|integer|min:0',
            'duracion_minutos' => 'nullable|integer|min:0|max:59',
            'suspendida' => 'nullable|boolean',
            'observacion_suspension' => 'nullable|string',

        ]);

        if ($request->input('ayudante_1_id') != null) {
            $request->validate([
                'ayudante_1_id' => 'exists:empleados,id|nullable|different:cirujano_id',
            ], [
                'ayudante_1_id.different' => 'El ayudante 1 debe ser distinto a los demas.',
            ]);
        }
        if ($request->input('ayudante_2_id') != null) {
            $request->validate([
                'ayudante_2_id' => 'exists:empleados,id|nullable|different:ayudante_1_id',
            ], [
                'ayudante_2_id.different' => 'El ayudante 2 debe ser distinto a los demas.',
            ]);
        }
        if ($request->input('ayudante_3_id') != null) {
            $request->validate([
                'ayudante_3_id' => 'exists:empleados,id|nullable|different:ayudante_1_id|different:ayudante_2_id',
            ], [
                'ayudante_3_id.different' => 'El ayudante 3 debe ser distinto a los demas.',
            ]);
        }
        if ($request->input('procedimiento_2_id') != null) {
            $request->validate([
                'procedimiento_2_id' => 'exists:procedimientos,id|nullable|different:procedimiento_id',
            ], [
                'procedimiento_2_id.different' => 'El Procedimiento 2 debe ser distinto a Procedimiento.',
            ]);
        }
        if ($request->input('instrumentador_2_id') != null) {
            $request->validate([
                'instrumentador_2_id' => 'exists:empleados,id|nullable|different:instrumentador_id',
            ], [
                'instrumentador_2_id.different' => 'El Instrumentador 2 debe ser distinto a Insturmentador.',
            ]);
        }
        if ($request->input('enfermero_2_id') != null) {
            $request->validate([
                'enfermero_2_id' => 'exists:empleados,id|nullable|different:enfermero_id',
            ], [
                'enfermero_2_id.different' => 'El Enfermero 2 debe ser distinto a Enfermero.',
            ]);
        }
        if ($request->input('tipo_anestesia_2_id') != null) {
            $request->validate([
                'tipo_anestesia_2_id' => 'exists:tipo_anestesias,id|nullable|different:tipo_anestesia_id',
            ], [
                'tipo_anestesia_2_id.different' => 'El tipo de anestesia 2 debe ser distinto a tipo de anestesia.',
            ]);
        }


        $cirugia->paciente_id = $request->input('paciente_id');
        $cirugia->especialidad_id = $request->input('especialidad_id');
        $cirugia->procedimiento_id = $request->input('procedimiento_id');
        $cirugia->procedimiento_2_id = $request->input('procedimiento_2_id');
        $cirugia->quirofano_id = $request->input('quirofano_id');
        $cirugia->cirujano_id = $request->input('cirujano_id');
        $cirugia->ayudante_1_id = $request->input('ayudante_1_id');
        $cirugia->ayudante_2_id = $request->input('ayudante_2_id');
        $cirugia->ayudante_3_id = $request->input('ayudante_3_id');
        $cirugia->anestesista_id = $request->input('anestesista_id');
        $cirugia->tipo_anestesia_id = $request->input('tipo_anestesia_id');
        $cirugia->tipo_anestesia_2_id = $request->input('tipo_anestesia_2_id');
        $cirugia->instrumentador_id = $request->input('instrumentador_id');
        $cirugia->instrumentador_2_id = $request->input('instrumentador_2_id');
        $cirugia->enfermero_id = $request->input('enfermero_id');
        $cirugia->enfermero_2_id = $request->input('enfermero_2_id');

        $cirugia->fecha_cirugia = $request->input('fecha_cirugia');
        $cirugia->hora_cirugia = $request->input('hora_cirugia');

        //Formatear duración
        $horas = $request->input('duracion_horas', 0);
        $minutos = $request->input('duracion_minutos', 0);
        $duracion = sprintf('%02d:%02d', $horas, $minutos);
        $cirugia->duracion = $duracion;

        if ($request->input('urgencia') != null) {
            $cirugia->urgencia = true;
        } else {
            $cirugia->urgencia = false;
        }

        if ($request->input('obito') != null) {
            $cirugia->obito = true;
        } else {
            $cirugia->obito = false;
        }

        $cirugia->suspendida = $request->has('suspendida');
        $cirugia->observacion_suspension = $cirugia->suspendida ? $request->input('observacion_suspension') : null;

        $cirugia->modificado_por = auth()->id();

        $cirugia->save();
        if ($request->input('action') === 'cargar_medicamentos') {
            return redirect()->route('cirugias.medicamentos', $cirugia);
        }
        return redirect()->route('cirugias.index');
    }

    public function destroy(Cirugia $cirugia)
    {
        $cirugia->delete();
        return redirect()->route('cirugias.index');
    }

    public function estadisticas(Request $request)
    {
        $validated = $request->validate([
            'desde' => 'nullable|date|before_or_equal:today',
            'hasta' => 'nullable|date|after_or_equal:desde|before_or_equal:today',
        ], [
            'desde.date' => 'La fecha de inicio no tiene un formato válido.',
            'desde.before_or_equal' => 'La fecha de inicio no puede ser futura.',
            'hasta.date' => 'La fecha de fin no tiene un formato válido.',
            'hasta.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'hasta.before_or_equal' => 'La fecha de fin no puede ser futura.',
        ]);
        $desde = $validated['desde'] ?? null;
        $hasta = $validated['hasta'] ?? null;
        $especialidadId = $request->input('especialidad_id');
        $especialidades = \App\Models\Especialidad::quirurgicas()->orderBy('nombre')->get();
        $aniosDisponibles = \App\Models\Cirugia::select(DB::raw('YEAR(fecha_cirugia) as anio'))
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        $anio = $request->input('anio');
        $cirujanoId = $request->input('cirujano_id');

        // Base query reutilizable (excluye cirugías suspendidas)
        $baseQuery = \App\Models\Cirugia::query()
            ->where('suspendida', false)
            ->when($desde && $hasta, function ($query) use ($desde, $hasta) {
                return $query->whereBetween('fecha_cirugia', [$desde, $hasta]);
            }, function ($query) use ($anio) {
                if ($anio) {
                    return $query->whereYear('fecha_cirugia', $anio);
                }
                return $query;
            })
            ->when($especialidadId, function ($query, $especialidadId) {
                return $query->where('especialidad_id', $especialidadId);
            })
            ->when($cirujanoId, function ($query, $cirujanoId) {
                return $query->where(function ($q) use ($cirujanoId) {
                    $q->where('cirujano_id', $cirujanoId)
                        ->orWhere('ayudante_1_id', $cirujanoId)
                        ->orWhere('ayudante_2_id', $cirujanoId)
                        ->orWhere('ayudante_3_id', $cirujanoId);
                });
            });

        // Obtener cirujanos disponibles para el filtro (cirujano o ayudante, excluyendo suspendidas)
        $cirujanosDisponibles = \App\Models\Empleado::whereIn('id', function ($query) use ($especialidadId) {
            $query->select('cirujano_id')
                ->from('cirugias')
                ->where('suspendida', false)
                ->when($especialidadId, function ($q, $especialidadId) {
                    return $q->where('especialidad_id', $especialidadId);
                })
                ->whereNotNull('cirujano_id')
                ->union(
                    DB::table('cirugias')
                        ->select('ayudante_1_id')
                        ->where('suspendida', false)
                        ->when($especialidadId, function ($q, $especialidadId) {
                            return $q->where('especialidad_id', $especialidadId);
                        })
                        ->whereNotNull('ayudante_1_id')
                )
                ->union(
                    DB::table('cirugias')
                        ->select('ayudante_2_id')
                        ->where('suspendida', false)
                        ->when($especialidadId, function ($q, $especialidadId) {
                            return $q->where('especialidad_id', $especialidadId);
                        })
                        ->whereNotNull('ayudante_2_id')
                )
                ->union(
                    DB::table('cirugias')
                        ->select('ayudante_3_id')
                        ->where('suspendida', false)
                        ->when($especialidadId, function ($q, $especialidadId) {
                            return $q->where('especialidad_id', $especialidadId);
                        })
                        ->whereNotNull('ayudante_3_id')
                );
        })
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        $total = $baseQuery->count();

        if ($desde && $hasta) {
            $fechaInicio = \Carbon\Carbon::parse($desde)->startOfDay();
            $fechaFin = \Carbon\Carbon::parse($hasta)->endOfDay();
            $diasDiferencia = max(1, $fechaInicio->diffInDays($fechaFin) + 1);
            $cantMeses = max(1, $diasDiferencia / 30.4375);
            $cantSemanas = max(1, $diasDiferencia / 7);
        } else {
            $cantMeses = 12;
            $cantSemanas = 52;
        }

        $promedioMensual = round($total / $cantMeses, 1);
        $promedioSemanal = round($total / $cantSemanas, 1);

        // Cirugías por cirujano (incluyendo participación como ayudante 1, 2 o 3)
        $subQueryCirujano = (clone $baseQuery)->select('cirujano_id as cirujano_id')->whereNotNull('cirujano_id')
            ->unionAll((clone $baseQuery)->select('ayudante_1_id as cirujano_id')->whereNotNull('ayudante_1_id'))
            ->unionAll((clone $baseQuery)->select('ayudante_2_id as cirujano_id')->whereNotNull('ayudante_2_id'))
            ->unionAll((clone $baseQuery)->select('ayudante_3_id as cirujano_id')->whereNotNull('ayudante_3_id'));

        $porCirujanoCounts = DB::table(DB::raw("({$subQueryCirujano->toSql()}) as participaciones"))
            ->mergeBindings($subQueryCirujano->getQuery())
            ->select('cirujano_id', DB::raw('COUNT(*) as total'))
            ->groupBy('cirujano_id')
            ->orderByDesc('total')
            ->get();

        $empleadosMap = \App\Models\Empleado::whereIn('id', $porCirujanoCounts->pluck('cirujano_id'))->get()->keyBy('id');

        $porCirujano = $porCirujanoCounts->map(function ($item) use ($empleadosMap) {
            $emp = $empleadosMap->get($item->cirujano_id);
            return (object) [
                'cirujano_id' => $item->cirujano_id,
                'total' => $item->total,
                'get_cirujano' => $emp
            ];
        });

        $cirujanoLabels = $porCirujano->take(5)->map(function ($item) {
            $c = $item->get_cirujano;
            return $c ? $c->nombre . ' ' . $c->apellido : 'Sin asignar';
        })->values();

        $cirujanoValores = $porCirujano->take(5)->pluck('total')->values();

        // Top enfermeros/as
        $topEnfermeros = (clone $baseQuery)
            ->select('enfermero_id', DB::raw('COUNT(*) as total'))
            ->groupBy('enfermero_id')
            ->with('get_enfermero')
            ->get()
            ->sortByDesc('total')
            ->take(5);

        $enfermeroLabels = $topEnfermeros->map(function ($item) {
            return optional($item->get_enfermero)->nombre . ' ' . optional($item->get_enfermero)->apellido;
        });

        $enfermeroValores = $topEnfermeros->pluck('total');

        // Top instrumentadores
        $topInstrumentadors = (clone $baseQuery)
            ->select('instrumentador_id', DB::raw('COUNT(*) as total'))
            ->groupBy('instrumentador_id')
            ->with('get_instrumentador')
            ->get()
            ->sortByDesc('total')
            ->take(5);

        $instrumentadorLabels = $topInstrumentadors->map(function ($item) {
            return optional($item->get_instrumentador)->nombre . ' ' . optional($item->get_instrumentador)->apellido;
        });

        $instrumentadorValores = $topInstrumentadors->pluck('total');

        // Distribución por mes
        $porMes = (clone $baseQuery)
            ->select(DB::raw('MONTH(fecha_cirugia) as mes'), DB::raw('COUNT(*) as total'))
            ->groupBy(DB::raw('MONTH(fecha_cirugia)'))
            ->orderBy(DB::raw('MONTH(fecha_cirugia)'))
            ->get();

        $porMes->transform(function ($item) {
            $item->mes_nombre = \Carbon\Carbon::create()->month($item->mes)->translatedFormat('F');
            return $item;
        });

        $porMesLabels = $porMes->pluck('mes')->map(function ($m) {
            return \Carbon\Carbon::create()->month($m)->translatedFormat('F');
        });

        $porMesValores = $porMes->pluck('total');
        $porMes = $porMes->sortBy('mes')->values();

        // Urgentes y programadas
        $urgentes = (clone $baseQuery)->where('urgencia', '1')->count();
        $programadas = (clone $baseQuery)->where('urgencia', '0')->count();

        // Top procedimientos / cirugías más realizadas
        $subQueryProcedimientos = (clone $baseQuery)->select('procedimiento_id as procedimiento_id')->whereNotNull('procedimiento_id')
            ->unionAll((clone $baseQuery)->select('procedimiento_2_id as procedimiento_id')->whereNotNull('procedimiento_2_id'));

        $procedimientosCounts = DB::table(DB::raw("({$subQueryProcedimientos->toSql()}) as participaciones"))
            ->mergeBindings($subQueryProcedimientos->getQuery())
            ->select('procedimiento_id', DB::raw('COUNT(*) as total'))
            ->groupBy('procedimiento_id')
            ->orderByDesc('total')
            ->get();

        $procedimientosMap = \App\Models\Procedimiento::whereIn('id', $procedimientosCounts->pluck('procedimiento_id'))->get()->keyBy('id');

        $topProcedimientos = $procedimientosCounts->map(function ($item) use ($procedimientosMap) {
            $proc = $procedimientosMap->get($item->procedimiento_id);
            return (object) [
                'procedimiento_id' => $item->procedimiento_id,
                'total' => $item->total,
                'get_procedimiento' => $proc
            ];
        });

        $procedimientoLabels = $topProcedimientos->take(5)->map(function ($item) {
            return optional($item->get_procedimiento)->nombre_procedimiento ?? 'Sin especificar';
        })->values();

        $procedimientoValores = $topProcedimientos->take(5)->pluck('total')->values();



        // Por tipo de anestesia
        $porAnestesia = (clone $baseQuery)
            ->select('tipo_anestesia_id', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo_anestesia_id')
            ->with('get_tipo_anestesia')
            ->get();
        $anioSeleccionado = request('anio') ?? \Carbon\Carbon::parse($desde)->year;
        return view('cirugias.estadisticas', compact(
            'porCirujano',
            'topEnfermeros',
            'topInstrumentadors',
            'topProcedimientos',
            'procedimientoLabels',
            'procedimientoValores',
            'instrumentadorLabels',
            'instrumentadorValores',
            'enfermeroLabels',
            'enfermeroValores',
            'porMes',
            'porMesLabels',
            'porMesValores',
            'urgentes',
            'programadas',
            'porAnestesia',
            'promedioMensual',
            'promedioSemanal',
            'cirujanoLabels',
            'cirujanoValores',
            'anioSeleccionado',
            'aniosDisponibles',
            'total',
            'promedioMensual',
            'promedioSemanal',
            'especialidadId',
            'especialidades',
            'cirujanosDisponibles',
            'cirujanoId'
        ));
    }

    public function medicamentos(Cirugia $cirugia)
    {
        $quirofanoServicio = $this->resolverServicioQuirofano();

        if (!$quirofanoServicio) {
            return redirect()->back()->withErrors(['error' => 'No se encontró el servicio de Quirófano. Creá un servicio llamado "Quirófano" en Servicios y cargale stock.']);
        }

        $stocks = \App\Models\Stock::where('servicio_id', $quirofanoServicio->id)
            ->where('cantidad_act', '>', 0)
            ->with('get_medicamento')
            ->get();

        $consumidos = \App\Models\Historial_stock::where('comentario', 'cirugia ' . $cirugia->id)
            ->where('cantidad', '<', 0)
            ->with('get_stock.get_medicamento')
            ->get();

        return view('cirugias.medicamentos', compact('cirugia', 'stocks', 'consumidos'));
    }

    public function guardarMedicamentos(Request $request, Cirugia $cirugia)
    {
        $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'cantidad' => 'required|integer|min:1',
        ], [
            'stock_id.required' => 'Debe seleccionar un insumo/medicamento.',
            'stock_id.exists' => 'El stock seleccionado no es válido.',
            'cantidad.required' => 'Debe ingresar la cantidad.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
        ]);

        $quirofanoServicio = $this->resolverServicioQuirofano();

        if (!$quirofanoServicio) {
            abort(500, 'No se encontró ningún servicio con "quirófano" o "cirugía" en el nombre (tabla servicios). Entrá a /servicios, fijate el nombre exacto y avisame para ajustar la búsqueda.');
        }

        $cantidad = (int) $request->cantidad;

        // Si algo falla se lanza una ValidationException: Laravel revierte la transacción
        // y vuelve al formulario con el error y los datos cargados.
        DB::transaction(function () use ($request, $cirugia, $quirofanoServicio, $cantidad) {
            // Bloqueamos la fila para que dos cargas simultáneas no dejen el stock en negativo
            $stock = \App\Models\Stock::lockForUpdate()->findOrFail($request->stock_id);

            if ($stock->servicio_id != $quirofanoServicio->id) {
                throw ValidationException::withMessages([
                    'stock_id' => 'El stock seleccionado no pertenece al servicio de Quirófano.',
                ]);
            }

            if ($stock->cantidad_act < $cantidad) {
                throw ValidationException::withMessages([
                    'cantidad' => 'No hay suficiente stock de este lote. Stock disponible: ' . $stock->cantidad_act,
                ]);
            }

            $this->registrarConsumoCirugia($cirugia, $stock, $cantidad);
        });

        return redirect()->route('cirugias.medicamentos', $cirugia)->with('success', 'Medicamento cargado y descontado del stock correctamente.');
    }

    /**
     * Carga por lector de código de barras (USB, funciona como teclado).
     * Cada lectura descuenta "unidades" (por defecto 1) del stock de Quirófano.
     *
     *  1) Si el código es de un lote de Quirófano, descuenta de ese lote.
     *  2) Si no, y está en el catálogo de códigos, descuenta de los lotes de ese insumo
     *     en Quirófano (primero el que vence antes).
     *  3) Si no está en ninguno, o no alcanza el stock, informa el error y no descuenta nada.
     */
    public function escanearMedicamento(Request $request, Cirugia $cirugia)
    {
        $data = $request->validate([
            'barcode'  => 'required|string|max:100',
            'unidades' => 'nullable|integer|min:1|max:999',
        ], [
            'barcode.required' => 'No se recibió ningún código de barras.',
            'unidades.integer' => 'Las unidades por lectura deben ser un número entero.',
            'unidades.min'     => 'Las unidades por lectura deben ser al menos 1.',
            'unidades.max'     => 'Las unidades por lectura no pueden superar 999.',
        ]);

        $barcode  = trim($data['barcode']);
        $unidades = (int) ($data['unidades'] ?? 1);

        $servicio = $this->resolverServicioQuirofano();

        if (!$servicio) {
            return redirect()->back()->withErrors(['barcode' => 'No se encontró el servicio de Quirófano. Creá un servicio llamado "Quirófano" en Servicios y cargale stock.']);
        }

        $mensaje = DB::transaction(function () use ($barcode, $unidades, $servicio, $cirugia) {
            // 1) ¿El código corresponde a un lote puntual de Quirófano?
            $lotes = \App\Models\Stock::where('servicio_id', $servicio->id)
                ->where('barcode', $barcode)
                ->lockForUpdate()
                ->get();

            if ($lotes->isNotEmpty()) {
                $nombre = optional($lotes->first()->get_medicamento)->nombre;
            } else {
                // 2) No es un lote de Quirófano: identificamos el insumo por el catálogo o por un lote de otro servicio
                $catalogo  = \App\Models\InsumoBarcode::where('barcode', $barcode)->with('medicamento')->first();
                $loteAjeno = \App\Models\Stock::where('barcode', $barcode)->with('get_medicamento')->first();

                $medicamentoId = optional($catalogo)->medicamento_id ?? optional($loteAjeno)->medicamento_id;

                if (!$medicamentoId) {
                    throw ValidationException::withMessages([
                        'barcode' => 'El código "' . $barcode . '" no está registrado. Cargalo primero en el stock de ' . $servicio->nombre . '.',
                    ]);
                }

                $nombre = optional(optional($catalogo)->medicamento)->nombre
                    ?? optional(optional($loteAjeno)->get_medicamento)->nombre;

                // Solo lotes del servicio de Quirófano, el que vence antes primero
                $lotes = \App\Models\Stock::where('servicio_id', $servicio->id)
                    ->where('medicamento_id', $medicamentoId)
                    ->where('cantidad_act', '>', 0)
                    ->orderBy('fecha_vencimiento')
                    ->lockForUpdate()
                    ->get();

                if ($lotes->isEmpty()) {
                    $otrosServicios = \App\Models\Stock::where('medicamento_id', $medicamentoId)
                        ->where('servicio_id', '!=', $servicio->id)
                        ->with('get_servicio')
                        ->get()
                        ->pluck('get_servicio.nombre')
                        ->filter()
                        ->unique()
                        ->implode(', ');

                    throw ValidationException::withMessages([
                        'barcode' => $otrosServicios !== ''
                            ? "{$nombre} pertenece a {$otrosServicios} y no se puede usar en cirugías. Solo se descuentan insumos de {$servicio->nombre}."
                            : "No hay stock de {$nombre} en {$servicio->nombre}.",
                    ]);
                }
            }

            $nombre     = $nombre ?? 'el insumo escaneado';
            $disponible = (int) $lotes->sum('cantidad_act');

            if ($disponible < $unidades) {
                throw ValidationException::withMessages([
                    'barcode' => "Stock insuficiente de {$nombre} en {$servicio->nombre} (disponible: {$disponible}, necesitás: {$unidades}).",
                ]);
            }

            $restante  = $unidades;
            $loteUsado = null;

            foreach ($lotes as $stock) {
                if ($restante <= 0) {
                    break;
                }

                $aDescontar = min($restante, (int) $stock->cantidad_act);
                if ($aDescontar <= 0) {
                    continue;
                }

                $this->registrarConsumoCirugia($cirugia, $stock, $aDescontar);
                $restante -= $aDescontar;
                $loteUsado = $loteUsado ?? $stock->lote;
            }

            return "Descontado: {$unidades} × {$nombre} (lote {$loteUsado}). Quedan " . ($disponible - $unidades) . ' en ' . $servicio->nombre . '.';
        });

        return redirect()->route('cirugias.medicamentos', $cirugia)
            ->with('success', $mensaje);
    }

    /**
     * Descuenta "$cantidad" del lote y lo registra en el historial de la cirugía.
     * Si ese lote ya se cargó hoy para esta cirugía, suma a la misma fila (la lista queda limpia).
     * Tiene que llamarse dentro de una transacción, con el lote ya bloqueado.
     */
    private function registrarConsumoCirugia(Cirugia $cirugia, \App\Models\Stock $stock, int $cantidad): void
    {
        $stock->decrement('cantidad_act', $cantidad);

        $movimiento = \App\Models\Historial_stock::where('cirugia_id', $cirugia->id)
            ->where('stock_id', $stock->id)
            ->where('comentario', 'cirugia ' . $cirugia->id)
            ->where('cantidad', '<', 0)
            ->whereDate('fecha', now()->toDateString())
            ->lockForUpdate()
            ->first();

        if ($movimiento) {
            $movimiento->cantidad = $movimiento->cantidad - $cantidad;
            $movimiento->save();

            return;
        }

        \App\Models\Historial_stock::create([
            'stock_id'    => $stock->id,
            'cirugia_id'  => $cirugia->id,
            'cantidad'    => -$cantidad,
            'fecha'       => now()->toDateString(),
            'empleado_id' => $cirugia->cirujano_id,
            'paciente_id' => $cirugia->paciente_id,
            'comentario'  => 'cirugia ' . $cirugia->id,
            'creado_por'  => auth()->id(),
        ]);
    }

    /**
     * Devuelve al stock una parte (o todo) de una carga de la cirugía.
     *  - "cantidad": unidades a devolver (por defecto 1).
     *  - "todo": devuelve toda la carga y elimina la fila.
     */
    public function eliminarMedicamento(Request $request, Cirugia $cirugia, \App\Models\Historial_stock $historial)
    {
        if ($historial->comentario !== 'cirugia ' . $cirugia->id || $historial->cantidad >= 0) {
            return redirect()->back()->withErrors(['error' => 'El registro de historial no pertenece a esta cirugía.']);
        }

        $request->validate([
            'cantidad' => 'nullable|integer|min:1',
        ], [
            'cantidad.integer' => 'La cantidad a devolver debe ser un número entero.',
            'cantidad.min'     => 'La cantidad a devolver debe ser al menos 1.',
        ]);

        $mensaje = DB::transaction(function () use ($request, $historial) {
            $historial = \App\Models\Historial_stock::lockForUpdate()->findOrFail($historial->id);
            $cargadas  = abs((int) $historial->cantidad);
            $devolver  = $request->boolean('todo') ? $cargadas : (int) ($request->input('cantidad') ?? 1);

            if ($devolver > $cargadas) {
                throw ValidationException::withMessages([
                    'cantidad' => "Solo hay {$cargadas} unidad(es) cargada(s) de este insumo en la cirugía.",
                ]);
            }

            $stock = \App\Models\Stock::lockForUpdate()->find($historial->stock_id);
            if ($stock) {
                $stock->increment('cantidad_act', $devolver);
            }

            if ($devolver === $cargadas) {
                $historial->delete();

                return 'Carga eliminada y stock restaurado.';
            }

            $historial->cantidad = -($cargadas - $devolver);
            $historial->save();

            return "Se devolvieron {$devolver} unidad(es) al stock. Quedan " . ($cargadas - $devolver) . ' cargadas en la cirugía.';
        });

        return redirect()->route('cirugias.medicamentos', $cirugia)->with('success', $mensaje);
    }

    /**
     * Busca el servicio de Quirófano por nombre (parcial, sin importar mayúsculas
     * o acentos exactos). Ya no cae en un ID hardcodeado si no lo encuentra —
     * antes, si el orden de los servicios cambiaba, podía terminar apuntando a
     * un servicio completamente distinto sin ningún aviso.
     */
    private function resolverServicioQuirofano(): ?\App\Models\Servicio
    {
        // Probamos varios patrones posibles, del más específico al más amplio,
        // porque no sabemos con certeza cómo se llama el servicio en cada instalación.
        $patrones = ['%quir%fano%', '%cirug%'];

        foreach ($patrones as $patron) {
            $servicio = \App\Models\Servicio::where('nombre', 'like', $patron)->first();
            if ($servicio) {
                return $servicio;
            }
        }

        return null;
    }
}
