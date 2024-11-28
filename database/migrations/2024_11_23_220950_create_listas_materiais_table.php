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
        Schema::create('listas_materiais', function (Blueprint $table) {
            $table->id();
            $table->boolean('urgente')->nullable();
            $table->string('aplicacao');
            $table->date('prazo')->nullable();
            $table->date('data_prevista')->nullable();
            $table->unsignedBigInteger('id_solicitante');
            $table->unsignedBigInteger('id_status');
            $table->unsignedBigInteger('id_empresa');
            $table->foreign('id_solicitante')->references('id')->on('users');
            $table->foreign('id_status')->references('id')->on('status_lm');
            $table->foreign('id_empresa')->references('id')->on('empresas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listas_materiais');
    }
};
