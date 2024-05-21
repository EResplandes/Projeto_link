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
        Schema::create('historico_pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_status');
            $table->string('observacao');
            $table->foreign('id_pedido')->references('id')->on('pedidos');
            $table->foreign('id_status')->references('id')->on('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_pedidos');
    }
};
