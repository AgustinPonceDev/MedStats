<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('usuario_perfils', function (Blueprint $table) {
        $table->boolean('trazabilidad')->nullable()->after('estudios_medicos');
    });
}

public function down(): void
{
    Schema::table('usuario_perfils', function (Blueprint $table) {
        $table->dropColumn('trazabilidad');
    });
}
};
