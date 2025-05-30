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
        Schema::create('anexos_lm', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lm');
            $table->string('observacao');
            $table->text('anexo');
            $table->foreign('id_lm')->references('id')->on('listas_materiais');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anexos_lm');
    }
};
