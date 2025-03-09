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
        Schema::create('act_ciclo_insumo', function (Blueprint $table) {
            $table->id('act_ci_ins_id');
            $table->unsignedBigInteger('act_ci_id');
            $table->unsignedBigInteger('ins_id');
            $table->timestamps();

            $table->foreign('act_ci_id')->references('act_ci_id')->on('act_ciclo')->onDelete('cascade');
            $table->foreign('ins_id')->references('ins_id')->on('insumos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('act_ciclo_insumos');
    }
};
