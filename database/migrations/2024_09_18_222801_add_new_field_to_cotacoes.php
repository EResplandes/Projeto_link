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
        Schema::table('cotacoes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_empresa')->after('id')->nullable();
            $table->unsignedBigInteger('id_local')->after('id')->nullable();
            $table->foreign('id_empresa')->references('id')->on('empresas');
            $table->foreign('id_local')->references('id')->on('local');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cotacoes', function (Blueprint $table) {
            //
        });
    }
};
