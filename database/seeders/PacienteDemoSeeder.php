<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Datos de ejemplo: pacientes, salas/habitaciones/camas nuevas e internaciones (activas y con alta).
 */
class PacienteDemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // [dni, nombre, apellido, nacimiento, género, alergias]
        $pacientes = [
            ['50123401', 'Agustina', 'Benavídez', '1991-02-14', 'Femenino', null],
            ['50123402', 'Facundo', 'Arias', '1984-06-23', 'Masculino', 'Látex'],
            ['50123403', 'Brenda', 'Cardozo', '1999-09-02', 'Femenino', null],
            ['50123404', 'Leandro', 'Villalba', '1976-12-30', 'Masculino', 'Penicilina'],
            ['50123405', 'Rocío', 'Maidana', '2001-04-18', 'Femenino', null],
            ['50123406', 'Sebastián', 'Funes', '1968-08-07', 'Masculino', 'Yodo'],
            ['50123407', 'Milagros', 'Correa', '1994-11-25', 'Femenino', null],
            ['50123408', 'Gonzalo', 'Peña', '1989-01-09', 'Masculino', null],
            ['50123409', 'Abril', 'Ledesma', '2015-05-21', 'Femenino', 'Frutos secos'],
            ['50123410', 'Thiago', 'Montenegro', '2013-10-03', 'Masculino', null],
            ['50123411', 'Elena', 'Bustos', '1952-03-16', 'Femenino', 'Aspirina'],
            ['50123412', 'Rubén', 'Ávila', '1947-07-28', 'Masculino', null],
            ['50123413', 'Carolina', 'Suárez', '1986-12-12', 'Femenino', null],
            ['50123414', 'Maximiliano', 'Toledo', '1997-02-06', 'Masculino', 'Sulfas'],
            ['50123415', 'Noelia', 'Campos', '1979-09-19', 'Femenino', null],
            ['50123416', 'Ariel', 'Ponce', '1963-04-11', 'Masculino', 'Contraste yodado'],
            ['50123417', 'Florencia', 'Esquivel', '2004-06-30', 'Femenino', null],
            ['50123418', 'Damián', 'Cabral', '1981-10-17', 'Masculino', null],
            ['50123419', 'Lucía', 'Ibáñez', '1992-08-24', 'Femenino', 'Ibuprofeno'],
            ['50123420', 'Héctor', 'Moyano', '1958-01-05', 'Masculino', null],
            ['50123421', 'Paula', 'Rivas', '1973-05-27', 'Femenino', null],
            ['50123422', 'Joaquín', 'Delgado', '2008-03-08', 'Masculino', null],
            ['50123423', 'Valeria', 'Escobar', '1988-11-14', 'Femenino', 'Codeína'],
            ['50123424', 'Ignacio', 'Barrios', '1995-07-01', 'Masculino', null],
        ];

        $calles = ['Av. San Martín', 'Calle Belgrano', 'Av. Rivadavia', 'Calle Moreno', 'Calle Urquiza', 'Av. Mitre'];
        $cps = [[1, 1, 1], [1, 2, 2], [1, 3, 3]]; // pais, provincia, código postal
        foreach ($pacientes as $i => [$dni, $nombre, $apellido, $nac, $genero, $alergias]) {
            [$pais, $prov, $cp] = $cps[$i % 3];
            DB::table('pacientes')->updateOrInsert(
                ['dni' => $dni],
                [
                    'nombre'           => $nombre,
                    'apellido'         => $apellido,
                    'fecha_nacimiento' => $nac,
                    'genero'           => $genero,
                    'telefono'         => '11' . (50000000 + $i * 211973),
                    'alergias'         => $alergias,
                    'pais_id'          => $pais,
                    'provincia_id'     => $prov,
                    'cod_postal_id'    => $cp,
                    'direccion'        => $calles[$i % count($calles)] . ' ' . (200 + $i * 57),
                    'creado_por'       => 1,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]
            );
        }

        // Salas nuevas: [sala, descripción, [habitación => [camas]]]
        $salas = [
            ['Sala Pediátrica', 'Piso 4 - Ala Norte', ['401' => ['401-A', '401-B'], '402' => ['402-A', '402-B']]],
            ['Unidad Coronaria', 'Piso 5 - Alta Complejidad', ['501' => ['501-A', '501-B', '501-C']]],
            ['Sala de Recuperación', 'Planta baja - Contigua a quirófanos', ['R01' => ['R01-A', 'R01-B']]],
        ];
        foreach ($salas as [$nombreSala, $desc, $habitaciones]) {
            DB::table('salas')->updateOrInsert(['nombre' => $nombreSala], ['descripcion' => $desc, 'created_at' => $now, 'updated_at' => $now]);
            $salaId = DB::table('salas')->where('nombre', $nombreSala)->value('id');
            foreach ($habitaciones as $numero => $camas) {
                DB::table('habitacions')->updateOrInsert(['sala_id' => $salaId, 'numero' => (string) $numero], ['created_at' => $now, 'updated_at' => $now]);
                $habId = DB::table('habitacions')->where('sala_id', $salaId)->where('numero', (string) $numero)->value('id');
                foreach ($camas as $codigo) {
                    if (!DB::table('camas')->where('habitacion_id', $habId)->where('codigo', $codigo)->exists()) {
                        DB::table('camas')->insert(['habitacion_id' => $habId, 'codigo' => $codigo, 'ocupada' => false, 'created_at' => $now, 'updated_at' => $now]);
                    }
                }
            }
        }

        // Internaciones activas: [dni, código de cama, días atrás, observaciones]
        $activas = [
            ['50123409', '401-A', 2, 'Cuadro respiratorio, en observación'],
            ['50123410', '402-A', 1, 'Post-operatorio de apendicectomía'],
            ['50123411', '501-A', 4, 'Control cardiológico, monitoreo continuo'],
            ['50123412', '501-B', 3, 'Insuficiencia cardíaca descompensada'],
            ['50123416', 'R01-A', 0, 'Recuperación post-anestésica'],
        ];
        foreach ($activas as [$dni, $codigoCama, $dias, $obs]) {
            $paciente = DB::table('pacientes')->where('dni', $dni)->first();
            $cama = DB::table('camas')->where('codigo', $codigoCama)->first();
            if (!$paciente || !$cama || $cama->ocupada || $paciente->cama_id) {
                continue;
            }
            DB::table('ocupacion_camas')->insert([
                'cama_id'       => $cama->id,
                'paciente_id'   => $paciente->id,
                'fecha_ingreso' => now()->subDays($dias)->setTime(9 + $dias % 8, 15),
                'observaciones' => $obs,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
            DB::table('camas')->where('id', $cama->id)->update(['ocupada' => true]);
            DB::table('pacientes')->where('id', $paciente->id)->update(['cama_id' => $cama->id, 'habitacion_id' => $cama->habitacion_id]);
        }

        // Internaciones con alta (historial): [dni, código de cama, días de ingreso atrás, días de estadía]
        $altas = [
            ['50123401', '401-B', 20, 4], ['50123405', '402-B', 15, 3], ['50123413', '501-C', 12, 6],
            ['50123418', 'R01-B', 9, 1], ['50123420', '401-B', 40, 8],
        ];
        foreach ($altas as [$dni, $codigoCama, $atras, $estadia]) {
            $paciente = DB::table('pacientes')->where('dni', $dni)->first();
            $cama = DB::table('camas')->where('codigo', $codigoCama)->first();
            if (!$paciente || !$cama) {
                continue;
            }
            $ingreso = now()->subDays($atras)->setTime(10, 0);
            $existe = DB::table('ocupacion_camas')->where('cama_id', $cama->id)->where('paciente_id', $paciente->id)->exists();
            if (!$existe) {
                DB::table('ocupacion_camas')->insert([
                    'cama_id'       => $cama->id,
                    'paciente_id'   => $paciente->id,
                    'fecha_ingreso' => $ingreso,
                    'fecha_egreso'  => $ingreso->copy()->addDays($estadia)->setTime(12, 30),
                    'observaciones' => 'Alta médica sin complicaciones',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            }
        }
    }
}
