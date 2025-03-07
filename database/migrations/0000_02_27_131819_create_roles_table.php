<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id('rol_id');
            $table->string('rol_desc');
            $table->timestamps(); // Agregado para trazabilidad
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
