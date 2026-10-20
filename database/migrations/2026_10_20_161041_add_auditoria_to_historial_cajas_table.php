<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historial_cajas', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('empleado_id')
                  ->constrained('users')->nullOnDelete();
            $table->string('accion', 20)->default('avance')->after('estado_registrado');
            $table->string('estado_anterior')->nullable()->after('accion');
            $table->boolean('anulado')->default(false)->after('estado_anterior');
        });

        DB::table('historial_cajas')
            ->where('observaciones', 'like', 'Alta de nueva caja%')
            ->update(['accion' => 'alta']);
    }

    public function down(): void
    {
        Schema::table('historial_cajas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['accion', 'estado_anterior', 'anulado']);
        });
    }
};