<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estudio_medicos', function (Blueprint $table) {
            $table->foreignId('especialidad_id')->nullable()->after('paciente_id')
                ->constrained('especialidads')->nullOnDelete();
            $table->foreignId('estudio_id')->nullable()->after('especialidad_id')
                ->constrained('estudios')->nullOnDelete();
        });

        // "regiones" cambia de significado (de "qué regiones se vieron", texto libre,
        // a "cuántas regiones se vieron", numérico). En vez de perder el texto viejo,
        // lo guardamos aparte en "regiones_legacy" para que quede a mano.
        Schema::table('estudio_medicos', function (Blueprint $table) {
            $table->string('regiones_legacy')->nullable()->after('estudio_id');
        });

        DB::statement('UPDATE estudio_medicos SET regiones_legacy = regiones WHERE regiones IS NOT NULL');

        Schema::table('estudio_medicos', function (Blueprint $table) {
            $table->dropColumn('regiones');
        });

        Schema::table('estudio_medicos', function (Blueprint $table) {
            $table->unsignedTinyInteger('regiones')->nullable()->after('regiones_legacy');
        });

        // Si el texto viejo ya era puramente numérico (ej. alguien había cargado "2"),
        // lo migramos directo a la columna nueva. Asume motor MySQL/MariaDB (REGEXP).
        DB::statement("UPDATE estudio_medicos SET regiones = CAST(regiones_legacy AS UNSIGNED) WHERE regiones_legacy REGEXP '^[0-9]+$'");
    }

    public function down(): void
    {
        Schema::table('estudio_medicos', function (Blueprint $table) {
            $table->dropColumn('regiones');
        });

        Schema::table('estudio_medicos', function (Blueprint $table) {
            $table->string('regiones')->nullable();
        });

        DB::statement('UPDATE estudio_medicos SET regiones = regiones_legacy');

        Schema::table('estudio_medicos', function (Blueprint $table) {
            $table->dropColumn('regiones_legacy');
            $table->dropForeign(['especialidad_id']);
            $table->dropForeign(['estudio_id']);
            $table->dropColumn(['especialidad_id', 'estudio_id']);
        });
    }
};
