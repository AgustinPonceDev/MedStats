@extends('layouts.app')
@section('titulo', 'Historial del Stock')
@section('contenido')
    @php
        $estado = $stock->estadoStock();
        $estadoCfg = [
            'ok' => ['Stock normal', 'bg-emerald-50 text-emerald-700 border-emerald-200'],
            'aviso' => ['Stock bajo', 'bg-amber-50 text-amber-700 border-amber-200'],
            'critico' => ['Stock crítico', 'bg-red-50 text-red-700 border-red-200'],
        ][$estado];
        $vence = $stock->fecha_vencimiento ? \Carbon\Carbon::parse($stock->fecha_vencimiento) : null;
        $vencido = $vence && $vence->isPast();
    @endphp

    <div class="max-w-7xl mx-auto px-4 py-8">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest px-2">Detalle de stock</span>
        <h1
            class="text-2xl font-bold bg-gradient-to-r from-[#1B7D8F] via-[#2BA8A0] to-[#245360] text-transparent bg-clip-text drop-shadow-md px-2 mb-6">
            Historial del medicamento
        </h1>

        {{-- Ficha del insumo --}}
        <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden mb-6">
            <div class="h-1.5 bg-gradient-to-r from-[#1B7D8F] via-[#2BA8A0] to-[#245360]"></div>

            <div class="p-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
                {{-- Medicamento y lote --}}
                <div class="lg:col-span-2">
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-lg font-bold text-gray-800">{{ $stock->get_medicamento->nombre }}</h2>
                        <span
                            class="inline-flex items-center rounded-full border px-3 py-0.5 text-xs font-semibold {{ $estadoCfg[1] }}">
                            {{ $estadoCfg[0] }}
                        </span>
                    </div>

                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="rounded-lg bg-gray-50 border border-gray-100 px-3 py-2">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">Lote</p>
                            <p class="mt-0.5 font-mono text-base font-bold text-[#1B7D8F]">{{ $stock->lote }}</p>
                        </div>
                        <div class="rounded-lg bg-gray-50 border border-gray-100 px-3 py-2">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">Stock actual</p>
                            <p class="mt-0.5 text-base font-bold text-gray-800">{{ $stock->cantidad_act }} <span
                                    class="text-xs font-medium text-gray-400">u.</span></p>
                        </div>
                        <div class="rounded-lg bg-gray-50 border border-gray-100 px-3 py-2">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">Vencimiento</p>
                            <p class="mt-0.5 text-base font-bold {{ $vencido ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $vence ? $vence->format('d/m/Y') : '—' }}
                            </p>
                            @if ($vencido)
                                <p class="text-[10px] font-semibold text-red-600">Vencido</p>
                            @endif
                        </div>
                        <div class="rounded-lg bg-gray-50 border border-gray-100 px-3 py-2">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">Servicio</p>
                            <p class="mt-0.5 text-base font-bold text-gray-800">
                                {{ optional($stock->get_servicio)->nombre ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Código de barras --}}
                <div
                    class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-3 flex flex-col items-center justify-center text-center">
                    <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400 mb-1.5">Código de barras</p>

                    @if ($stock->barcode)
                        <svg id="barcode_svg" xmlns="http://www.w3.org/2000/svg" class="block"></svg>
                        <div class="mt-1 flex items-center gap-2">
                            <span id="barcode_texto"
                                class="font-mono text-xs font-semibold tracking-wider text-gray-700">{{ $stock->barcode }}</span>
                            <button type="button" id="btn_copiar_barcode" title="Copiar código"
                                class="rounded-md border border-gray-300 bg-white px-1.5 py-0 text-[11px] text-gray-600 hover:bg-gray-100">
                                Copiar
                            </button>
                        </div>
                    @else
                        <p class="text-sm text-gray-400">Este lote no tiene código de barras asignado.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Movimientos --}}
        <div class="bg-white shadow rounded-lg border border-gray-200 overflow-auto">
            <table class="table table-hover table-bordered shadow-sm text-center rounded">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border text-center">Cantidad</th>
                        <th class="px-4 py-2 border text-center">Fecha</th>
                        <th class="px-4 py-2 border">Médico</th>
                        <th class="px-4 py-2 border">Paciente</th>
                        <th class="px-4 py-2 border">Comentario</th>
                        <th class="px-4 py-2 border text-center">Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hist_item as $item)
                        @php
                            $fechaMov = $item->fecha ? \Carbon\Carbon::parse($item->fecha) : null;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td
                                class="px-4 py-2 border text-center font-bold {{ $item->cantidad < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                {{ $item->cantidad > 0 ? '+' : '' }}{{ $item->cantidad }}
                            </td>
                            <td class="px-4 py-2 border text-center">
                                @if ($fechaMov)
                                    {{ $fechaMov->format('d/m/Y') }}
                                    @if ($fechaMov->format('H:i:s') !== '00:00:00')
                                        <span class="block text-xs text-gray-400">{{ $fechaMov->format('H:i') }}</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-2 border">
                                {{ $item->get_empleado?->nombre }} {{ $item->get_empleado?->apellido }}
                                @if ($item->get_empleado)
                                    (Mat. {{ $item->get_empleado->matricula ?? 'S/M' }})
                                @endif
                            </td>
                            <td class="px-4 py-2 border">
                                {{ $item->get_paciente?->nombre }} {{ $item->get_paciente?->apellido }}
                                @if ($item->get_paciente)
                                    (DNI {{ $item->get_paciente->dni }})
                                @endif
                            </td>
                            <td class="px-4 py-2 border">{{ $item->comentario }}</td>
                            <td class="px-4 py-2 border text-center">{{ $item->get_creador->name ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-2 text-center text-gray-500">No hay movimientos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $hist_item->links() }}
        </div>
    </div>
@endsection

@if ($stock->barcode)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const codigo = @json($stock->barcode);

                // ---------- Code 128 sin librerías externas ----------
                // Cada patrón son los anchos alternados barra/espacio de un símbolo (índices 0-106).
                const CODE128 = [
                    '212222', '222122', '222221', '121223', '121322', '131222', '122213', '122312', '132212',
                    '221213',
                    '221312', '231212', '112232', '122132', '122231', '113222', '123122', '123221', '223211',
                    '221132',
                    '221231', '213212', '223112', '312131', '311222', '321122', '321221', '312212', '322112',
                    '322211',
                    '212123', '212321', '232121', '111323', '131123', '131321', '112313', '132113', '132311',
                    '211313',
                    '231113', '231311', '112133', '112331', '132131', '113123', '113321', '133121', '313121',
                    '211331',
                    '231131', '213113', '213311', '213131', '311123', '311321', '331121', '312113', '312311',
                    '332111',
                    '314111', '221411', '431111', '111224', '111422', '121124', '121421', '141122', '141221',
                    '112214',
                    '112412', '122114', '122411', '142112', '142211', '241211', '221114', '413111', '241112',
                    '134111',
                    '111242', '121142', '121241', '114212', '124112', '124211', '411212', '421112', '421211',
                    '212141',
                    '214121', '412121', '111143', '111341', '131141', '114113', '114311', '411113', '411311',
                    '113141',
                    '114131', '311141', '411131', '211412', '211214', '211232', '2331112'
                ];

                // Devuelve la cadena de anchos de todo el código, o null si hay caracteres no soportados
                function code128Anchos(texto) {
                    const valores = [];
                    if (/^\d{4,}$/.test(texto)) {
                        // Solo dígitos: Code C (dos dígitos por símbolo) para que quede más corto
                        let i = 0;
                        if (texto.length % 2 === 0) {
                            valores.push(105); // Start C
                        } else {
                            valores.push(104, texto.charCodeAt(0) - 32, 99); // Start B, 1.er dígito, pasa a C
                            i = 1;
                        }
                        for (; i < texto.length; i += 2) valores.push(parseInt(texto.substr(i, 2), 10));
                    } else {
                        valores.push(104); // Start B: ASCII imprimible
                        for (const ch of texto) {
                            const c = ch.charCodeAt(0);
                            if (c < 32 || c > 126) return null;
                            valores.push(c - 32);
                        }
                    }
                    let suma = valores[0];
                    for (let i = 1; i < valores.length; i++) suma += valores[i] * i;
                    valores.push(suma % 103); // dígito verificador
                    valores.push(106); // Stop
                    return valores.map(v => CODE128[v]).join('');
                }

                function dibujarCode128(svg, texto) {
                    const anchos = code128Anchos(texto);
                    if (!anchos) return false;

                    const margen = 10; // zona de silencio (en módulos)
                    let x = margen;
                    let barras = '';
                    for (let i = 0; i < anchos.length; i++) {
                        const w = parseInt(anchos[i], 10);
                        if (i % 2 === 0) barras += `<rect x="${x}" y="0" width="${w}" height="50"/>`;
                        x += w;
                    }
                    const total = x + margen;

                    svg.setAttribute('viewBox', `0 0 ${total} 50`);
                    svg.setAttribute('preserveAspectRatio', 'none');
                    svg.style.width = Math.round(total * 1.3) + 'px';
                    svg.style.maxWidth = '100%';
                    svg.style.height = '44px';
                    svg.innerHTML = `<g fill="#1f2937">${barras}</g>`;
                    return true;
                }

                const svg = document.getElementById('barcode_svg');
                if (svg && !dibujarCode128(svg, codigo)) svg.remove();

                // ---------- Botón copiar ----------
                const btn = document.getElementById('btn_copiar_barcode');
                if (btn) {
                    btn.addEventListener('click', function() {
                        const listo = () => {
                            btn.textContent = '¡Copiado!';
                            setTimeout(() => btn.textContent = 'Copiar', 1500);
                        };
                        if (navigator.clipboard && window.isSecureContext) {
                            navigator.clipboard.writeText(codigo).then(listo);
                        } else {
                            const t = document.createElement('textarea');
                            t.value = codigo;
                            document.body.appendChild(t);
                            t.select();
                            document.execCommand('copy');
                            t.remove();
                            listo();
                        }
                    });
                }
            });
        </script>
    @endpush
@endif
