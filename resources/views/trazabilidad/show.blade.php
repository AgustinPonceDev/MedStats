@extends('layouts.app')

@section('title', 'Mostrar Seguimiento')

@section('contenido')
<div class="container mx-auto p-4 max-w-6xl">
    
    <div class="mb-6">
        <a href="{{ route('trazabilidad.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors font-medium">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Volver al listado
        </a>
    </div>

    <!-- Encabezado de la Caja -->
   <div class="bg-white rounded-xl shadow-sm p-6 mb-12 border border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                <i data-lucide="box" class="inline-block w-6 h-6 text-indigo-500 mr-2 -mt-1"></i>
                {{ $caja->nombre }} ({{ $caja->codigo }})
            </h2>
            <p class="text-gray-500 mt-1">Trazabilidad del ciclo de esterilización y uso.</p>
        </div>

        @if(auth()->user()->hasAccess('admin'))
            <div class="flex flex-col items-end gap-3">
                <span class="px-4 py-2 rounded-lg font-bold text-sm bg-indigo-100 text-indigo-800 border border-indigo-200">
                    Estado Actual: {{ $caja->estado_actual }}
                 </span>
            </div>
        @endif
    </div>

    <!-- Contenedor Horizontal de Trazabilidad -->
    <div class="w-full overflow-x-auto pb-12">
        <div class="flex items-center min-w-[900px] px-8 pt-28">
            
            <!-- CAMBIO 1: Ahora traemos los últimos 5 movimientos -->
            @foreach($caja->historiales->take(-4) as $index => $movimiento)
                
                <div class="relative flex flex-col items-center flex-1">
                    
                    <div class="absolute -top-24 flex flex-col items-center">
                        <div class="p-4 bg-white rounded-2xl border-2 border-gray-100 shadow-md">
                            @if($movimiento->estado_registrado == 'Depósito Estéril')
                                <i data-lucide="layers" class="w-12 h-12 text-indigo-600"></i>
                            
                            @elseif($movimiento->estado_registrado == 'Esterilizada')
                                <i data-lucide="shield-check" class="w-12 h-12 text-emerald-600"></i>
                            
                                
                            @elseif($movimiento->estado_registrado == 'Almacenada')
                                <i data-lucide="archive" class="w-12 h-12 text-slate-500"></i>
                            
                            @elseif($movimiento->estado_registrado == 'En Uso')
                                <i data-lucide="scissors" class="w-12 h-12 text-rose-500"></i>
                            
                            @else
                                <i data-lucide="box" class="w-12 h-12 text-gray-400"></i>
                            @endif
                        </div>
                    </div>

                    <div class="w-10 h-10 bg-[#0d7f8c] border-4 border-white ring-4 ring-[#eef5f6] rounded-full flex items-center justify-center z-10">
                    </div>

                    <div class="mt-8 text-center w-36">
                        <h4 class="text-base font-bold text-gray-800">{{ $movimiento->estado_registrado }}</h4>
                        
                        <!-- CAMBIO 3: Si es Esterilizada, mostramos el método abajo del título -->
                        @if($movimiento->estado_registrado == 'Esterilizada')
                            <p class="text-xs font-bold text-[#0d7f8c] mb-1">
                                ({{ $caja->tipo_esterilizacion ?? 'No especificado' }})
                            </p>
                        @endif

                        <p class="text-sm text-gray-500 mt-1">{{ $movimiento->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs font-semibold text-gray-700 mt-1">
                        <i data-lucide="user" class="inline-block w-3 h-3 mr-1 -mt-0.5 opacity-70"></i>
                         {{ $movimiento->empleado->name ?? 'Sistema' }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $movimiento->created_at->format('H:i') }}</p>
                        
                        @if($movimiento->empleado)
                            <p class="text-sm font-medium text-gray-700 mt-2 truncate" title="{{ $movimiento->empleado->nombre }}">
                                {{ $movimiento->empleado->nombre }}
                            </p>
                        @endif

                        @if($movimiento->cirugia_id)
                            <p class="text-xs text-red-600 font-bold mt-1">Cirugía #{{ $movimiento->cirugia_id }}</p>
                        @endif
                    </div>
                </div>

                @if(!$loop->last)
                 <div class="flex-auto border-t-4 border-[#0d7f8c] opacity-80 -mt-24"></div>
                @endif
            @endforeach

            @if(auth()->user()->hasAccess('admin'))
                @php
                    // CAMBIO 4: Actualizamos el "Cerebro" de los botones para el ciclo de 5 pasos
                    $flujo_normal = [
                        'Esterilizada' => 'Depósito Estéril',
                        'Depósito Estéril' => 'Almacenada',
                        'Almacenada' => 'En Uso', 
                        'En Uso' => 'Esterilizada'
                    ];
                    $flujo_inverso = [
                        'Depósito Estéril' => 'Esterilizada',
                        'Almacenada' => 'Depósito Estéril',
                        'En Uso' => 'Almacenada', 
                        'Esterilizada' => 'En Uso',
                    ];
                    $siguienteEstado = $flujo_normal[$caja->estado_actual] ?? 'Deposito esteril';
                    $estadoAnterior = $flujo_inverso[$caja->estado_actual] ?? 'Deposito esteril';
                @endphp

                <!-- ACÁ AGREGAMOS EL IF PARA OCULTAR LOS BOTONES SI ESTÁ EN DESUSO -->
                @if(!$caja->trashed())
                    <div class="relative flex flex-col items-center min-w-[100px] ml-4">
                        
                        <div class="absolute -top-20 flex gap-2 items-center">
                            
                            <form action="{{ route('trazabilidad.estado', $caja->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="accion" value="retroceder">
                                <button type="submit" class="p-2 bg-red-50 rounded-lg border border-red-200 shadow-sm hover:shadow hover:bg-red-100 hover:border-red-300 transition-all group flex items-center justify-center cursor-pointer" title="Deshacer a {{ $estadoAnterior }}">
                                    <i data-lucide="rotate-ccw" class="w-5 h-5 text-red-500 group-hover:-rotate-45 transition-transform"></i>
                                </button>
                            </form>

                            <form action="{{ route('trazabilidad.estado', $caja->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="accion" value="avanzar">
                                <button type="submit" class="p-2 bg-blue-50 rounded-lg border border-blue-200 shadow-sm hover:shadow hover:bg-blue-100 hover:border-blue-300 transition-all group flex items-center justify-center cursor-pointer" title="Avanzar a {{ $siguienteEstado }}">
                                    <i data-lucide="plus" class="w-5 h-5 text-blue-600 group-hover:scale-110 transition-transform"></i>
                                </button>
                            </form>
                        </div>

                        <div class="mt-10 text-center w-full">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Acción Manual</h4>
                        </div>
                    </div>
                @endif <!-- CIERRE DEL NUEVO IF -->
            @endif

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection