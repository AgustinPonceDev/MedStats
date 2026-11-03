<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Datos de ejemplo: servicio de imágenes, profesiones, personal (empleados) y usuarios por perfil.
 */
class PersonalDemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Nombre exacto que usa EstudioMedicoController para descontar insumos
        DB::table('servicios')->updateOrInsert(
            ['nombre' => 'Diagnóstico por imágenes'],
            ['descripcion' => 'Servicio de Rayos X y Tomografía', 'created_at' => $now, 'updated_at' => $now]
        );
        DB::table('servicios')->updateOrInsert(
            ['nombre' => 'Quirófano Central'],
            ['descripcion' => 'Insumos de uso quirúrgico', 'created_at' => $now, 'updated_at' => $now]
        );

        // Profesiones nuevas (rol 1 = Médico)
        $profesiones = [
            ['Médico Radiólogo', 'Informa estudios de Rayos X y Tomografía', 1],
            ['Médico Clínico', 'Clínica médica y guardia', 1],
            ['Traumatólogo', 'Especialista en traumatología y ortopedia', 1],
            ['Ginecólogo', 'Especialista en ginecología y obstetricia', 1],
        ];
        foreach ($profesiones as [$nombre, $desc, $rol]) {
            DB::table('profesions')->updateOrInsert(
                ['nombre_profesion' => $nombre],
                ['descripcion' => $desc, 'rol_id' => $rol, 'created_at' => $now, 'updated_at' => $now]
            );
        }
        $prof = DB::table('profesions')->pluck('id', 'nombre_profesion');

        // [dni, nombre, apellido, profesión, matrícula, nacimiento]
        $empleados = [
            ['27345111', 'Andrea', 'Molina', 'Médico Radiólogo', 'R-10021', '1980-03-12'],
            ['29456222', 'Federico', 'Benítez', 'Médico Radiólogo', 'R-10022', '1983-07-30'],
            ['26567333', 'Claudia', 'Ibarra', 'Médico Clínico', 'C-20031', '1978-11-05'],
            ['30678444', 'Gustavo', 'Paz', 'Médico Clínico', 'C-20032', '1985-01-22'],
            ['28789555', 'Marcelo', 'Quiroga', 'Traumatólogo', 'T-30041', '1981-09-14'],
            ['31890666', 'Daniela', 'Acosta', 'Traumatólogo', 'T-30042', '1987-05-02'],
            ['27901777', 'Patricia', 'Vera', 'Ginecólogo', 'G-40051', '1979-12-19'],
            ['32012888', 'Hernán', 'Luna', 'Cirujano General', 'M-50061', '1986-04-08'],
            ['29123999', 'Julieta', 'Ortiz', 'Cardiólogo', 'M-50062', '1984-08-27'],
            ['33234111', 'Pablo', 'Domínguez', 'Anestesiólogo', 'A-60071', '1988-02-16'],
            ['30345222', 'Verónica', 'Cabrera', 'Anestesiólogo', 'A-60072', '1982-06-09'],
            ['34456333', 'Ramiro', 'Giménez', 'Anestesiólogo', 'A-60073', '1990-10-31'],
            ['31567444', 'Lorena', 'Navarro', 'Enfermero Profesional', 'E-70081', '1989-03-25'],
            ['32678555', 'Jorge', 'Medina', 'Enfermero Profesional', 'E-70082', '1991-07-11'],
            ['33789666', 'Camila', 'Rojas', 'Enfermero Profesional', 'E-70083', '1993-12-03'],
            ['34890777', 'Silvana', 'Peralta', 'Enfermero Profesional', 'E-70084', '1992-09-18'],
            ['30901888', 'Emiliano', 'Ferreyra', 'Instrumentador Quirúrgico', 'I-80091', '1988-01-29'],
            ['32012999', 'Natalí', 'Gómez', 'Instrumentador Quirúrgico', 'I-80092', '1990-05-14'],
            ['33123000', 'Walter', 'Sosa', 'Instrumentador Quirúrgico', 'I-80093', '1987-11-23'],
        ];

        $calles = ['Av. Belgrano', 'Calle Mitre', 'Av. Corrientes', 'Calle Sarmiento', 'Av. Libertador', 'Calle Alsina'];
        foreach ($empleados as $i => [$dni, $nombre, $apellido, $profesion, $matricula, $nac]) {
            if (!isset($prof[$profesion])) {
                continue;
            }
            DB::table('empleados')->updateOrInsert(
                ['dni' => $dni],
                [
                    'nombre'           => $nombre,
                    'apellido'         => $apellido,
                    'fecha_nacimiento' => $nac,
                    'telefono'         => '11' . (40000000 + $i * 137531),
                    'pais_id'          => 1,
                    'provincia_id'     => 1,
                    'cod_postal_id'    => 1,
                    'direccion'        => $calles[$i % count($calles)] . ' ' . (100 + $i * 83),
                    'profesion_id'     => $prof[$profesion],
                    'matricula'        => $matricula,
                    'creado_por'       => 1,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ]
            );
        }

        // Usuarios de ejemplo, uno por perfil (contraseña: password). No pisa usuarios existentes.
        $imagenes = DB::table('servicios')->where('nombre', 'Diagnóstico por imágenes')->value('id');
        $perfil = fn (string $n) => DB::table('usuario_perfils')->where('perfil', $n)->value('id');
        $usuarios = [
            ['Dra. Andrea Molina', 'imagenes@medstats.com', $perfil('Diagnóstico de Imagen'), $imagenes],
            ['Técnico de Trazabilidad', 'trazabilidad@medstats.com', $perfil('Trazabilidad'), null],
            ['Estadísticas Hospital', 'estadisticas@medstats.com', $perfil('Estadísticas'), null],
        ];
        foreach ($usuarios as [$name, $email, $role, $servicio]) {
            if (!$role || DB::table('users')->where('email', $email)->exists()) {
                continue;
            }
            DB::table('users')->insert([
                'name'        => $name,
                'email'       => $email,
                'password'    => Hash::make('password'),
                'role'        => $role,
                'servicio_id' => $servicio,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }
    }
}
