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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->float('valor');
            $table->boolean('urgente');
            $table->string('anexo')->unique();
            $table->enum('status', ['Monica', 'Emival'])->default('Emival');
            $table->dateTime('dt_aprovacao_diretor')->nullable();
            $table->dateTime('dt_aprovacao_presidencia')->nullable();
            $table->unsignedBigInteger('fk_usuario');
            $table->unsignedBigInteger('fk_status');
            $table->unsignedBigInteger('fk_empresa');
            $table->foreign('fk_usuario')->references('id')->on('users');
            $table->foreign('fk_status')->references('id')->on('status');
            $table->foreign('fk_empresa')->references('id')->on('empresas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
