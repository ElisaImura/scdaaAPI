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
        Schema::create('control_det', function (Blueprint $table) {
            $table->id('con_id');
            $table->unsignedBigInteger('act_id'); // Clave foránea
            $table->integer('con_cant');
            $table->integer('con_vigor');
            $table->timestamps();

            // Definir la clave foránea
            $table->foreign('act_id')->references('act_id')->on('actividades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_det');
    }
};
