<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::select("
            SELECT COUNT(*) as cnt
            FROM information_schema.columns
            WHERE table_schema = DATABASE()
              AND table_name = 'estudio_medicos'
              AND column_name = 'tipo_estudio'
        ");

        if ($exists[0]->cnt > 0) {
            Schema::table('estudio_medicos', function (Blueprint $table) {
                $table->dropColumn('tipo_estudio');
            });
        }
    }

    public function down(): void
    {
        Schema::table('estudio_medicos', function (Blueprint $table) {
            $table->string('tipo_estudio', 150)->after('ia');
        });
    }
};
