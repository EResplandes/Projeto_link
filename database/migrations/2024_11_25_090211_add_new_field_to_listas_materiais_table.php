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
        Schema::table('listas_materiais', function (Blueprint $table) {
            $table->unsignedBigInteger('id_local')->after('id_comprador')->nullable();
            $table->foreign('id_local')->references('id')->on('locais_lm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listas_materiais', function (Blueprint $table) {
            //
        });
    }
};
