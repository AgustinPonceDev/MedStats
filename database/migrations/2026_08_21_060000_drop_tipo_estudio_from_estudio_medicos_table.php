<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estudio_medicos', function (Blueprint $table) {
            $table->dropColumn('tipo_estudio');
        });
    }

    public function down(): void
    {
        Schema::table('estudio_medicos', function (Blueprint $table) {
            $table->string('tipo_estudio', 150)->after('ia');
        });
    }
};
