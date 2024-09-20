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
        Schema::create('produtos_cotacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cotacao');
            $table->string('produto');
            $table->float('valor');
            $table->string('fornecedor');
            $table->char('link_produto');
            $table->char('link_imagem');
            $table->enum('entrega', ['Grátis', 'Não inclusa'])->nullable();
            $table->foreign('id_cotacao')->references('id')->on('cotacoes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos_cotacoes');
    }
};
