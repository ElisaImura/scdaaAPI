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
        Schema::create('act_ciclo', function (Blueprint $table) {
            $table->id('act_ci_id');
            $table->unsignedBigInteger('act_id');
            $table->unsignedBigInteger('ci_id')->nullable();
            $table->unsignedBigInteger('uss_id')->nullable();
            $table->timestamps();

            // Definir claves foráneas correctamente
            $table->foreign('act_id')->references('act_id')->on('actividades')->onDelete('cascade');
            $table->foreign('uss_id')->references('uss_id')->on('users')->onDelete('set null');
            $table->foreign('ci_id')->references('ci_id')->on('ciclos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('act_ciclo'); // Corrección aquí
    }
};
