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
        Schema::create('tipos_cultivo', function (Blueprint $table) { // 🔹 Nombre corregido
            $table->id('tpCul_id'); // Clave primaria
            $table->string('tpCul_nombre', 191); // Nombre del tipo de cultivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_cultivo'); // 🔹 Nombre corregido
    }
};
