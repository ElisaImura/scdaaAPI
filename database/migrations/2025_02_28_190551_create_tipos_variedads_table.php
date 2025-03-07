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
        Schema::create('tipos_variedad', function (Blueprint $table) {
            $table->id('tpVar_id');
            $table->unsignedBigInteger('tpCul_id'); // Asegurar que es UNSIGNED
            $table->string('tpVar_nombre', 191);
            $table->timestamps();
        
            $table->foreign('tpCul_id')->references('tpCul_id')->on('tipos_cultivo')->onDelete('cascade'); // 🔹 Nombre corregido
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_variedad');
    }
};

