<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de ejemplo de Trazabilidad: cajas quirúrgicas en todos los estados, con su
 * historial completo (alta, avances, algún retroceso) siguiendo el flujo real de la app:
 * Almacenada → En Uso → Esterilizada → Depósito Estéril → Almacenada.
 */
class CajaDemoSeeder extends Seeder
{
    private const FLUJO = ['Almacenada', 'En Uso', 'Esterilizada', 'Depósito Estéril'];

    public function run(): void
    {
        mt_srand(99);

        $userId = DB::table('users')->where('email', 'trazabilidad@medstats.com')->value('id')
            ?? DB::table('users')->value('id');
        $cirugias = DB::table('cirugias')->where('fecha_cirugia', '<=', now()->toDateString())->pluck('id')->all();

        // [código, nombre, tipo, descripción, estado final, vueltas completas, con retroceso, en desuso]
        $cajas = [
            ['CAJ-030', 'Cirugía Mayor', 'Autoclave', '12 pinzas Kelly, 4 separadores, 2 tijeras Mayo, porta agujas', 'Almacenada', 3, false, false],
            ['CAJ-031', 'Cirugía Mayor', 'Autoclave', '10 pinzas, 3 separadores Farabeuf, tijeras', 'En Uso', 2, false, false],
            ['CAJ-032', 'Cirugía Menor', 'Autoclave', '6 pinzas, 1 tijera, porta agujas, bisturí N°4', 'Esterilizada', 2, true, false],
            ['CAJ-033', 'Cirugía Menor', 'Óxido de Etileno', '6 pinzas, 2 tijeras, mango de bisturí', 'Depósito Estéril', 1, false, false],
            ['CAJ-034', 'Traumatología', 'Autoclave', 'Osteótomos, martillo, 2 separadores Hohmann, curetas', 'En Uso', 2, false, false],
            ['CAJ-035', 'Traumatología', 'Óxido de Etileno', 'Motor, 4 fresas, guías, calibrador', 'Esterilizada', 1, false, false],
            ['CAJ-036', 'Cardiovascular', 'Autoclave', 'Pinzas vasculares, separador esternal, tijeras de Potts', 'Depósito Estéril', 2, false, false],
            ['CAJ-037', 'Cardiovascular', 'Óxido de Etileno', 'Clamps, pinzas De Bakey, porta agujas fino', 'Almacenada', 1, false, false],
            ['CAJ-038', 'Neurocirugía', 'Autoclave', 'Craneótomo, pinzas bipolares, separadores cerebrales', 'En Uso', 1, false, false],
            ['CAJ-039', 'Laparoscopía', 'Óxido de Etileno', '3 trocares, pinza Maryland, tijera laparoscópica, aspirador', 'Esterilizada', 3, true, false],
            ['CAJ-040', 'Ginecología', 'Autoclave', 'Espéculos, pinzas de Pozzi, histerómetro, curetas', 'Almacenada', 2, false, false],
            ['CAJ-041', 'Cirugía Menor', 'Autoclave', '5 pinzas, tijera, porta agujas', 'Almacenada', 1, false, true],
            ['CAJ-042', 'Traumatología', 'Autoclave', 'Caja de yeso: sierra, tijeras, separadores', 'Depósito Estéril', 2, false, false],
        ];

        foreach ($cajas as [$codigo, $nombre, $tipo, $desc, $final, $vueltas, $retroceso, $desuso]) {
            if (DB::table('caja_quirurgicas')->where('codigo', $codigo)->exists()) {
                continue;
            }

            // Cantidad de pasos desde el alta hasta el estado final
            $pasos = $vueltas * count(self::FLUJO) + array_search($final, self::FLUJO, true);
            $fecha = now()->subDays(mt_rand(20, 110))->setTime(8, 0);
            $estado = 'Almacenada';
            $eventos = [[
                'estado' => $estado, 'accion' => 'alta', 'anterior' => null, 'anulado' => false,
                'obs' => 'Alta de nueva caja en el sistema.', 'fecha' => $fecha->copy(),
            ]];

            for ($p = 1; $p <= $pasos; $p++) {
                $fecha = $fecha->copy()->addHours(mt_rand(20, 70));
                $nuevo = self::FLUJO[(array_search($estado, self::FLUJO, true) + 1) % count(self::FLUJO)];

                // Un avance que se deshace: queda anulado y se registra el retroceso
                if ($retroceso && $p === 2) {
                    $eventos[] = $this->evento($nuevo, 'avance', $estado, true, $fecha);
                    $fecha = $fecha->copy()->addMinutes(12);
                    $eventos[] = [
                        'estado' => $estado, 'accion' => 'retroceso', 'anterior' => $nuevo, 'anulado' => false,
                        'obs' => 'Retroceso de "' . $nuevo . '" a "' . $estado . '" por Técnico de Trazabilidad',
                        'fecha' => $fecha->copy(),
                    ];
                    $fecha = $fecha->copy()->addHours(2);
                }

                $eventos[] = $this->evento($nuevo, 'avance', $estado, false, $fecha);
                $estado = $nuevo;
            }

            $cajaId = DB::table('caja_quirurgicas')->insertGetId([
                'codigo'              => $codigo,
                'nombre'              => $nombre,
                'tipo_esterilizacion' => $tipo,
                'descripcion'         => $desc,
                'estado_actual'       => $estado,
                'created_at'          => $eventos[0]['fecha'],
                'updated_at'          => end($eventos)['fecha'],
                'deleted_at'          => $desuso ? end($eventos)['fecha']->copy()->addDays(2) : null,
            ]);

            foreach ($eventos as $e) {
                $enUso = $e['estado'] === 'En Uso' && $e['accion'] === 'avance';
                DB::table('historial_cajas')->insert([
                    'caja_quirurgicas_id' => $cajaId,
                    'empleado_id'         => null,
                    'user_id'             => $e['accion'] === 'alta' ? null : $userId,
                    'cirugia_id'          => $enUso && $cirugias ? $cirugias[array_rand($cirugias)] : null,
                    'estado_registrado'   => $e['estado'],
                    'accion'              => $e['accion'],
                    'estado_anterior'     => $e['anterior'],
                    'anulado'             => $e['anulado'],
                    'observaciones'       => $e['obs'],
                    'created_at'          => $e['fecha'],
                    'updated_at'          => $e['fecha'],
                ]);
            }
        }
    }

    private function evento(string $nuevo, string $accion, string $anterior, bool $anulado, $fecha): array
    {
        return [
            'estado' => $nuevo, 'accion' => $accion, 'anterior' => $anterior, 'anulado' => $anulado,
            'obs' => 'Avance de "' . $anterior . '" a "' . $nuevo . '" por Técnico de Trazabilidad',
            'fecha' => $fecha->copy(),
        ];
    }
}
