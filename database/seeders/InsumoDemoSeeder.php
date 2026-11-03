<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\ConsumeStock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de ejemplo: medicamentos/insumos y lotes de stock en distintos estados
 * (ok, aviso, crítico, por vencer y vencido) con su historial de movimientos.
 * No carga códigos de barras.
 */
class InsumoDemoSeeder extends Seeder
{
    use ConsumeStock;

    public function run(): void
    {
        mt_srand(2026);
        $now = now();

        $nuevos = [
            'Suero Fisiológico 500ml', 'Ringer Lactato 500ml', 'Lidocaína 2% 20ml', 'Bupivacaína 0.5% 20ml',
            'Cefazolina 1g', 'Heparina Sódica 5000UI', 'Adrenalina 1mg/ml', 'Atropina 1mg/ml', 'Dexametasona 8mg',
            'Ondansetrón 8mg', 'Sutura Vicryl 2-0', 'Sutura Nylon 3-0', 'Gasas Estériles 10x10', 'Guantes Quirúrgicos 7.5',
            'Jeringa Descartable 10ml', 'Catéter Venoso 20G', 'Bisturí Descartable N°11', 'Campo Quirúrgico Estéril',
            // Insumos fijos de Diagnóstico por Imágenes (nombres exactos que usa el formulario de estudios)
            'Contraste 50ml', 'Contraste 100ml', 'Jeringa Prellenada', 'Aguja de Punción 18G',
        ];
        foreach ($nuevos as $nombre) {
            DB::table('medicamentos')->updateOrInsert(['nombre' => $nombre], ['created_at' => $now, 'updated_at' => $now]);
        }

        $servicios = DB::table('servicios')->pluck('id', 'nombre');
        $meds = DB::table('medicamentos')->pluck('id', 'nombre');

        // [medicamento, servicio, lote, vencimiento, cantidad final, umbral aviso, umbral crítico]
        $lotes = [
            // Insumos quirúrgicos (se consumen en las cirugías de ejemplo)
            ['Suero Fisiológico 500ml', 'Cirugía General', 'DEMO-SF-001', '2028-03-31', 400, 100, 50],
            ['Ringer Lactato 500ml', 'Cirugía General', 'DEMO-RL-001', '2028-01-31', 260, 100, 50],
            ['Lidocaína 2% 20ml', 'Cirugía General', 'DEMO-LI-001', '2027-10-31', 90, 50, 30],
            ['Cefazolina 1g', 'Cirugía General', 'DEMO-CE-001', '2027-08-15', 140, 60, 30],
            ['Sutura Vicryl 2-0', 'Cirugía General', 'DEMO-VI-001', '2029-05-31', 180, 60, 30],
            ['Gasas Estériles 10x10', 'Cirugía General', 'DEMO-GA-001', '2029-02-28', 900, 200, 100],
            ['Guantes Quirúrgicos 7.5', 'Cirugía General', 'DEMO-GU-001', '2028-11-30', 38, 50, 30],     // aviso
            ['Bupivacaína 0.5% 20ml', 'Cirugía General', 'DEMO-BU-001', '2027-12-31', 22, 40, 25],        // crítico
            ['Bisturí Descartable N°11', 'Cirugía General', 'DEMO-BI-001', '2030-01-31', 150, 50, 25],
            // Quirófano central y otros servicios
            ['Sutura Nylon 3-0', 'Quirófano Central', 'DEMO-NY-001', '2029-07-31', 120, 50, 25],
            ['Campo Quirúrgico Estéril', 'Quirófano Central', 'DEMO-CQ-001', '2028-09-30', 300, 80, 40],
            ['Jeringa Descartable 10ml', 'Quirófano Central', 'DEMO-JD-001', '2029-04-30', 1200, 300, 150],
            ['Catéter Venoso 20G', 'Quirófano Central', 'DEMO-CV-001', '2028-06-30', 45, 60, 30],        // aviso
            ['Heparina Sódica 5000UI', 'Cardiología', 'DEMO-HE-001', '2026-12-05', 70, 40, 20],            // por vencer
            ['Adrenalina 1mg/ml', 'Cardiología', 'DEMO-AD-001', '2027-05-31', 55, 40, 20],
            ['Atropina 1mg/ml', 'Cardiología', 'DEMO-AT-001', '2026-10-05', 35, 30, 15],                   // vencido
            ['Dexametasona 8mg', 'Clínica Médica', 'DEMO-DE-001', '2028-02-29', 210, 60, 30],
            ['Ondansetrón 8mg', 'Clínica Médica', 'DEMO-ON-001', '2027-09-30', 15, 40, 20],               // crítico
            ['Amoxicilina 1g', 'Pediatría', 'DEMO-AM-001', '2027-04-30', 130, 50, 30],
            ['Omeprazol 40mg', 'Clínica Médica', 'DEMO-OM-001', '2028-08-31', 320, 80, 40],
            ['Ranitidina 50mg', 'Traumatología', 'DEMO-RA-001', '2026-11-20', 48, 40, 20],                 // por vencer
            ['Losartán 50mg', 'Cardiología', 'DEMO-LO-001', '2028-10-31', 260, 80, 40],
            ['Atorvastatina 20mg', 'Cardiología', 'DEMO-AO-001', '2029-01-31', 190, 60, 30],
            // Diagnóstico por imágenes
            ['Contraste 50ml', 'Diagnóstico por imágenes', 'DEMO-C50-001', '2027-07-31', 42, 50, 30],      // aviso
            ['Contraste 100ml', 'Diagnóstico por imágenes', 'DEMO-C100-001', '2027-07-31', 120, 50, 30],
            ['Jeringa Prellenada', 'Diagnóstico por imágenes', 'DEMO-JP-001', '2027-10-31', 18, 40, 25],   // crítico
            ['Aguja de Punción 18G', 'Diagnóstico por imágenes', 'DEMO-AP-001', '2029-03-31', 160, 50, 25],
        ];

        $motivos = [
            'Reposición de farmacia', 'Consumo en guardia', 'Entrega a internación', 'Uso en procedimiento programado',
        ];

        foreach ($lotes as [$med, $serv, $lote, $venc, $final, $aviso, $critico]) {
            if (!isset($meds[$med], $servicios[$serv]) || DB::table('stocks')->where('lote', $lote)->exists()) {
                continue;
            }

            $stockId = DB::table('stocks')->insertGetId([
                'medicamento_id'    => $meds[$med],
                'servicio_id'       => $servicios[$serv],
                'lote'              => $lote,
                'fecha_vencimiento' => $venc,
                'cantidad_act'      => $final,
                'umbral_aviso'      => $aviso,
                'umbral_critico'    => $critico,
                'creado_por'        => 1,
                'created_at'        => now()->subDays(130),
                'updated_at'        => $now,
            ]);

            DB::table('historial_stocks')->insert([
                'stock_id'   => $stockId,
                'cantidad'   => $final,
                'fecha'      => now()->subDays(130)->setTime(9, 0),
                'comentario' => 'Carga inicial',
                'creado_por' => 1,
                'created_at' => now()->subDays(130),
                'updated_at' => now()->subDays(130),
            ]);

            // Los insumos de imágenes y cirugía generan su historial con los estudios/cirugías
            if (in_array($serv, ['Diagnóstico por imágenes', 'Cirugía General'], true)) {
                continue;
            }

            // 1 a 3 salidas de ejemplo (la carga inicial se ajusta sola para que cierre el stock)
            for ($i = 0, $n = mt_rand(1, 3); $i < $n; $i++) {
                $fecha = now()->subDays(mt_rand(3, 100))->setTime(mt_rand(8, 19), mt_rand(0, 59))->toDateTimeString();
                $this->consumir($stockId, mt_rand(3, 12), $fecha, [
                    'empleado_id' => mt_rand(1, 4) === 1 ? null : DB::table('empleados')->inRandomOrder()->value('id'),
                    'paciente_id' => DB::table('pacientes')->inRandomOrder()->value('id'),
                    'comentario'  => $motivos[mt_rand(0, count($motivos) - 1)],
                    'creado_por'  => 1,
                ]);
            }
        }
    }
}
