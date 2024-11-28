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
        Schema::table('lancamentos_materiais', function (Blueprint $table) {
            $table->integer('numero_nota')->after('nota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lancamentos_materiais', function (Blueprint $table) {
            //
        });
    }
};
