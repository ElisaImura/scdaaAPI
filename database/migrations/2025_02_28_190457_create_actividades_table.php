<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id('act_id'); // Clave primaria
            $table->unsignedBigInteger('tpAct_id'); // Clave foránea
            $table->string('act_fecha', 191);
            $table->string('act_desc', 191)->nullable();
            $table->integer('act_estado');
            $table->string('act_foto', 191)->nullable(); // Puede ser NULL
            $table->timestamps();

            // Definir la clave foránea
            $table->foreign('tpAct_id')->references('tpAct_id')->on('tipos_actividades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
