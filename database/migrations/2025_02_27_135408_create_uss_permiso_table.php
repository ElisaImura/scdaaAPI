<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuario_permiso', function (Blueprint $table) {
            $table->id('up_id');
            $table->unsignedBigInteger('uss_id');
            $table->foreign('uss_id')->references('uss_id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('perm_id')->nullable();
            $table->foreign('perm_id')->references('perm_id')->on('permisos')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_permiso');
    }
};
