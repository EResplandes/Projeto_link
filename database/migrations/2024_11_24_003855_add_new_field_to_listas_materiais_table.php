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
            $table->string('lm')->after('id');
            $table->unsignedBigInteger('id_comprador')->after('id_empresa')->nullable();
            $table->foreign('id_comprador')->references('id')->on('users');
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
