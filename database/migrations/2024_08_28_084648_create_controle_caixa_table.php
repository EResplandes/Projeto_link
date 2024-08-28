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
        Schema::create('controle_caixa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_caixa');
            $table->dateTime('dt_lancamento');
            $table->string('discriminacao');
            $table->float('debito')->nullable();
            $table->float('credito')->nullable();
            $table->float('saldo')->nullable();
            $table->string('observacao')->nullable();
            $table->enum('tipo_caixa', ['Caixa Rotativo', 'Ajuda de Custo']);
            $table->foreign('id_caixa')->references('id')->on('caixas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('controle_caixa');
    }
};
