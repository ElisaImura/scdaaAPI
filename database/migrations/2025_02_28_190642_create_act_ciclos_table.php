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
            $table->id('ci_id');
            $table->unsignedBigInteger('act_id');
            $table->unsignedBigInteger('uss_id');
            $table->timestamps();

            $table->foreign('act_id')->references('act_id')->on('actividades')->onDelete('cascade');
            $table->foreign('uss_id')->references('uss_id')->on('users')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('act_ciclos');
    }
};
