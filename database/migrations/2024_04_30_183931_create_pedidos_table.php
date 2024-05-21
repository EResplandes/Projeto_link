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
            $table->boolean('urgente')->nullable();
            $table->date('dt_vencimento')->nullable();
            $table->string('anexo');
            $table->unsignedBigInteger('id_link');
            $table->unsignedBigInteger('id_status');
            $table->unsignedBigInteger('id_empresa');
            $table->foreign('id_link')->references('id')->on('link');
            $table->foreign('id_status')->references('id')->on('status');
            $table->foreign('id_empresa')->references('id')->on('empresas');
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
