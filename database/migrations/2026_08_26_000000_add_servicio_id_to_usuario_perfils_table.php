<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuario_perfils', function (Blueprint $table) {
            $table->unsignedBigInteger('servicio_id')->nullable()->after('estudios_medicos');
            $table->foreign('servicio_id')->references('id')->on('servicios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('usuario_perfils', function (Blueprint $table) {
            $table->dropForeign(['servicio_id']);
            $table->dropColumn('servicio_id');
        });
    }
};
