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
        Schema::create('lancamentos_materiais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_material');
            $table->integer('quantidade_entregue');
            $table->date('dt_entrega');
            $table->string('nota')->nullable();
            $table->foreign('id_material')->references('id')->on('materiais_lm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamentos_materiais');
    }
};
