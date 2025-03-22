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
        Schema::create('ciclos', function (Blueprint $table) {
            $table->id('ci_id');
            $table->unsignedBigInteger('tpVar_id')->nullable();
            $table->unsignedBigInteger('uss_id')->nullable();
            $table->unsignedBigInteger('lot_id')->nullable();
            $table->string('ci_nombre');
            $table->date('ci_fechaini');
            $table->date('ci_fechafin')->nullable();
            $table->float('cos_rendi')->nullable();
            $table->float('cos_hume')->nullable();
            $table->float('sie_densidad')->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('tpVar_id')->references('tpVar_id')->on('tipos_variedad')->onDelete('set null');
            $table->foreign('uss_id')->references('uss_id')->on('users')->onDelete('set null');
            $table->foreign('lot_id')->references('lot_id')->on('lotes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclos');
    }
};
