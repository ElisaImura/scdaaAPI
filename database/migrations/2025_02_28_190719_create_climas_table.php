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
        Schema::create('climas', function (Blueprint $table) {
            $table->id('cl_id');
            $table->date('cl_fecha');
            $table->float('cl_viento')->nullable();
            $table->float('cl_temp')->nullable();
            $table->float('cl_hume')->nullable();
            $table->float('cl_lluvia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('climas');
    }
};
