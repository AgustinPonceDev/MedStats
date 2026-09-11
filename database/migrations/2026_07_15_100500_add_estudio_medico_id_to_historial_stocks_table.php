<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historial_stocks', function (Blueprint $table) {
            $table->foreignId('estudio_medico_id')->nullable()->after('stock_id')
                ->constrained('estudio_medicos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('historial_stocks', function (Blueprint $table) {
            $table->dropForeign(['estudio_medico_id']);
            $table->dropColumn('estudio_medico_id');
        });
    }
};
