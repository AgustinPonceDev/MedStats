<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\ConsumeStock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de ejemplo: cirugías realizadas entre fines de mayo y hoy (con consumo de insumos)
 * y cirugías programadas para las próximas semanas.
 */
class CirugiaDemoSeeder extends Seeder
{
    use ConsumeStock;

    public function run(): void
    {
        mt_srand(314);

        // Las cirugías existentes llegan hasta el 20/05/2026: si ya hay posteriores, no se duplica
        if (DB::table('cirugias')->where('fecha_cirugia', '>', '2026-05-20')->exists()) {
            return;
        }

        $procedimientos = DB::table('procedimientos')->whereNotNull('especialidad_id')->get(['id', 'especialidad_id']);
        $porProfesion = fn (int $profesionId) => DB::table('empleados')->where('profesion_id', $profesionId)->pluck('id')->all();
        $cirujanos = $porProfesion(1);
        $enfermeros = $porProfesion(3);
        $instrumentadores = $porProfesion(4);
        $anestesistas = $porProfesion(5);
        $pacientes = DB::table('pacientes')->pluck('id')->all();
        $quirofanos = DB::table('quirofanos')->pluck('id')->all();
        $anestesias = DB::table('tipo_anestesias')->pluck('id')->all();

        if ($procedimientos->isEmpty() || !$cirujanos || !$pacientes || !$quirofanos) {
            return;
        }

        $pick = fn (array $a) => $a[array_rand($a)];

        // Insumos que se descuentan en cada cirugía: [lote, mínimo, máximo]
        $insumos = [
            ['DEMO-SF-001', 1, 3], ['DEMO-GA-001', 8, 25], ['DEMO-VI-001', 1, 4], ['DEMO-BI-001', 1, 2],
            ['DEMO-CE-001', 1, 2], ['DEMO-LI-001', 0, 2], ['DEMO-RL-001', 0, 2], ['DEMO-GU-001', 0, 1], ['DEMO-BU-001', 0, 1],
        ];
        $stockIds = DB::table('stocks')->whereIn('lote', array_column($insumos, 0))->pluck('id', 'lote');

        $suspensiones = [
            'Paciente con fiebre el día de la cirugía.',
            'Falta de disponibilidad de quirófano por urgencia.',
            'Estudios prequirúrgicos incompletos.',
        ];

        $total = 40;
        $realizadas = 32;
        for ($i = 0; $i < $total; $i++) {
            $programada = $i >= $realizadas;
            $fecha = $programada
                ? now()->addDays(mt_rand(1, 30))
                : now()->subDays(mt_rand(1, 152));
            $hora = sprintf('%02d:%02d:00', mt_rand(7, 17), mt_rand(0, 3) * 15);
            $proc = $procedimientos->random();
            $local = mt_rand(1, 100) <= 15;
            $ayudantes = mt_rand(0, 2);
            $suspendida = !$programada && $i % 17 === 0;

            $cirugiaId = DB::table('cirugias')->insertGetId([
                'paciente_id'         => $pick($pacientes),
                'especialidad_id'     => $proc->especialidad_id,
                'procedimiento_id'    => $proc->id,
                'quirofano_id'        => $pick($quirofanos),
                'cirujano_id'         => $pick($cirujanos),
                'ayudante_1_id'       => $ayudantes >= 1 ? $pick($cirujanos) : null,
                'ayudante_2_id'       => $ayudantes >= 2 ? $pick($cirujanos) : null,
                'anestesista_id'      => $local || !$anestesistas ? null : $pick($anestesistas),
                'tipo_anestesia_id'   => $local ? 4 : ($anestesias ? $pick(array_diff($anestesias, [4])) : null),
                'instrumentador_id'   => $instrumentadores ? $pick($instrumentadores) : null,
                'enfermero_id'        => $enfermeros ? $pick($enfermeros) : null,
                'urgencia'            => !$programada && mt_rand(1, 100) <= 25,
                'obito'               => !$programada && !$suspendida && $i === 9,
                'suspendida'          => $suspendida,
                'observacion_suspension' => $suspendida ? $pick($suspensiones) : null,
                'duracion'            => $programada || $suspendida ? null : sprintf('%02d:%02d', mt_rand(0, 3), mt_rand(0, 11) * 5),
                'fecha_cirugia'       => $fecha->toDateString(),
                'hora_cirugia'        => $hora,
                'creado_por'          => 1,
                'created_at'          => $programada ? now() : $fecha->copy()->subDays(mt_rand(1, 6)),
                'updated_at'          => $programada ? now() : $fecha,
            ]);

            if ($programada || $suspendida) {
                continue;
            }

            foreach ($insumos as [$lote, $min, $max]) {
                if (!isset($stockIds[$lote]) || ($cantidad = mt_rand($min, $max)) === 0) {
                    continue;
                }
                $this->consumir($stockIds[$lote], $cantidad, $fecha->copy()->setTimeFromTimeString($hora)->toDateTimeString(), [
                    'cirugia_id'  => $cirugiaId,
                    'empleado_id' => $instrumentadores ? $pick($instrumentadores) : null,
                    'paciente_id' => DB::table('cirugias')->where('id', $cirugiaId)->value('paciente_id'),
                    'comentario'  => 'cirugia ' . $cirugiaId,
                    'creado_por'  => 1,
                ]);
            }
        }
    }
}
