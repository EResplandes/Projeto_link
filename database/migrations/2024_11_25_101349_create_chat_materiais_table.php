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
        Schema::create('chat_materiais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_material');
            $table->unsignedBigInteger('id_usuario');
            $table->text('mensagem');
            $table->foreign('id_material')->references('id')->on('materiais_lm');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_materiais');
    }
};
