<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\ConsumeStock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de ejemplo de Diagnóstico por Imágenes: catálogo de estudios por modalidad
 * (Rayos X / Tomografía) y estudios realizados durante el año con su consumo de insumos.
 */
class EstudioDemoSeeder extends Seeder
{
    use ConsumeStock;

    public function run(): void
    {
        mt_srand(77);
        $now = now();

        $catalogo = [
            'Rayos (X)' => [
                'Tórax frente', 'Tórax frente y perfil', 'Columna cervical', 'Columna lumbosacra', 'Pelvis',
                'Cadera', 'Rodilla', 'Hombro', 'Mano', 'Muñeca', 'Tobillo', 'Pie', 'Abdomen simple', 'Senos paranasales',
            ],
            'Tomografía' => [
                'TC de cerebro', 'TC de tórax', 'TC de abdomen y pelvis', 'TC de columna lumbar', 'TC de senos paranasales',
                'TC de cuello', 'Angio-TC de tórax', 'TC de rodilla',
            ],
        ];
        foreach ($catalogo as $modalidad => $estudios) {
            $espId = DB::table('especialidads')->where('nombre', $modalidad)->value('id');
            if (!$espId) {
                DB::table('especialidads')->insert(['nombre' => $modalidad, 'es_modalidad_imagen' => true, 'created_at' => $now, 'updated_at' => $now]);
                $espId = DB::table('especialidads')->where('nombre', $modalidad)->value('id');
            }
            foreach ($estudios as $nombre) {
                DB::table('estudios')->updateOrInsert(
                    ['especialidad_id' => $espId, 'nombre' => $nombre],
                    ['created_at' => $now, 'updated_at' => $now]
                );
            }
        }

        // Los estudios realizados se cargan una sola vez
        if (DB::table('estudio_medicos')->exists()) {
            return;
        }

        $rayos = DB::table('especialidads')->where('nombre', 'Rayos (X)')->value('id');
        $tomo = DB::table('especialidads')->where('nombre', 'Tomografía')->value('id');
        $estudiosPorModalidad = [
            $rayos => DB::table('estudios')->where('especialidad_id', $rayos)->get(['id', 'nombre']),
            $tomo  => DB::table('estudios')->where('especialidad_id', $tomo)->get(['id', 'nombre']),
        ];

        $solicitantes = DB::table('empleados')
            ->join('profesions', 'profesions.id', '=', 'empleados.profesion_id')
            ->where('profesions.rol_id', 1)
            ->pluck('empleados.id')->all();
        $internados = DB::table('pacientes')->whereNotNull('cama_id')->pluck('id')->all();
        $ambulatorios = DB::table('pacientes')->whereNull('cama_id')->pluck('id')->all();

        $stock = fn (string $lote) => DB::table('stocks')->where('lote', $lote)->value('id');
        $c50 = $stock('DEMO-C50-001');
        $c100 = $stock('DEMO-C100-001');
        $jeringa = $stock('DEMO-JP-001');
        $aguja = $stock('DEMO-AP-001');

        $descartables = ['Guantes y gasas', 'Gasas, alcohol y guantes', 'Jeringa y guantes', null];
        $informes = [
            'Sin hallazgos patológicos significativos.',
            'Imagen compatible con proceso inflamatorio leve. Correlacionar con clínica.',
            'Se observa lesión focal que requiere control evolutivo en 3 meses.',
            'Estudio dentro de parámetros normales para la edad.',
            'Cambios degenerativos leves. Sin signos de fractura reciente.',
            'Hallazgo incidental sin relevancia clínica actual.',
        ];

        // 70 estudios distribuidos entre enero y hoy
        $inicio = now()->startOfYear();
        $dias = $inicio->diffInDays(now());
        for ($i = 0; $i < 70; $i++) {
            $fecha = $inicio->copy()->addDays(mt_rand(0, $dias))->setTime(mt_rand(7, 19), mt_rand(0, 3) * 15);
            if ($fecha->isFuture()) {
                $fecha = now()->subHours(mt_rand(2, 30));
            }

            $esTomo = mt_rand(1, 100) <= 40;
            $modalidad = $esTomo ? $tomo : $rayos;
            $estudio = $estudiosPorModalidad[$modalidad]->random();
            $interno = $internados && mt_rand(1, 100) <= 30;
            $pacienteId = $interno ? $internados[array_rand($internados)] : $ambulatorios[array_rand($ambulatorios)];

            // Contraste solo en tomografías con contraste; las radiografías usan jeringa/aguja ocasionalmente
            $cont50 = $cont100 = $jer = $ag = 0;
            if ($esTomo && mt_rand(1, 100) <= 60) {
                mt_rand(0, 1) ? $cont100 = 1 : $cont50 = mt_rand(1, 2);
                $jer = 1;
            } elseif (!$esTomo && mt_rand(1, 100) <= 8) {
                $ag = 1;
            }

            $estudioId = DB::table('estudio_medicos')->insertGetId([
                'paciente_id'           => $pacienteId,
                'especialidad_id'       => $modalidad,
                'estudio_id'            => $estudio->id,
                'medico_solicitante_id' => $solicitantes[array_rand($solicitantes)],
                'ia'                    => $interno ? 'I' : 'A',
                'tipo_estudio'          => $estudio->nombre,
                'fecha'                 => $fecha->toDateString(),
                'hora_estudio'          => $fecha->format('H:i:s'),
                'regiones'              => $esTomo ? mt_rand(1, 3) : mt_rand(1, 2),
                'cont_50ml'             => $cont50,
                'cont_100ml'            => $cont100,
                'jeringa_prellenada'    => $jer,
                'descartables'          => $descartables[array_rand($descartables)],
                'otros_agujas'          => $ag ? 'Aguja de punción 18G' : null,
                'resultado'             => mt_rand(1, 100) <= 85 ? $informes[array_rand($informes)] : null,
                'created_at'            => $fecha,
                'updated_at'            => $fecha,
            ]);

            $mov = fn (?int $stockId, int $cant, string $nombre) => $stockId && $this->consumir($stockId, $cant, $fecha->toDateTimeString(), [
                'estudio_medico_id' => $estudioId,
                'empleado_id'       => DB::table('estudio_medicos')->where('id', $estudioId)->value('medico_solicitante_id'),
                'paciente_id'       => $pacienteId,
                'comentario'        => "Consumido en estudio médico #{$estudioId} ({$nombre})",
                'creado_por'        => 1,
            ]);
            $mov($c50, $cont50, 'Contraste 50ml');
            $mov($c100, $cont100, 'Contraste 100ml');
            $mov($jeringa, $jer, 'Jeringa Prellenada');
            $mov($aguja, $ag, 'Aguja de Punción 18G');
        }
    }
}
