<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUrgenciaToEstudioMedicosTable extends Migration
{
    public function up()
    {
        // Se chequea antes por si la columna ya se agregó a mano en alguna base
        if (!Schema::hasColumn('estudio_medicos', 'urgencia')) {
            Schema::table('estudio_medicos', function (Blueprint $table) {
                $table->boolean('urgencia')->default(false)->after('ia');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('estudio_medicos', 'urgencia')) {
            Schema::table('estudio_medicos', function (Blueprint $table) {
                $table->dropColumn('urgencia');
            });
        }
    }
}
