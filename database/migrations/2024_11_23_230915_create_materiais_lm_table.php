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
        Schema::create('materiais_lm', function (Blueprint $table) {
            $table->id();
            $table->text('descricao');
            $table->integer('quantidade');
            $table->string('unidade');
            $table->unsignedBigInteger('id_lm');
            $table->unsignedBigInteger('id_pedido')->nullable();
            $table->foreign('id_lm')->references('id')->on('listas_materiais');
            $table->foreign('id_pedido')->references('id')->on('pedidos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiais_lm');
    }
};
