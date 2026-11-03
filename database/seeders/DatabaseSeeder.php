<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

            $this->call([
            PaisSeeder::class,
            ProvinciaSeeder::class,
            CodigoPostalSeeder::class,
            ProcedimientoSeeder::class,
            // Datos de ejemplo para demostración (idempotentes, sin códigos de barras)
            PersonalDemoSeeder::class,
            PacienteDemoSeeder::class,
            InsumoDemoSeeder::class,
            EstudioDemoSeeder::class,
            CirugiaDemoSeeder::class,
            CajaDemoSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
