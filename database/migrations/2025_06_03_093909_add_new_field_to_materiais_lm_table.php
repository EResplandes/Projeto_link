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
        Schema::table('materiais_lm', function (Blueprint $table) {
            $table->boolean('adicionado_posteriormente')->default('0')->after('quantidade_autorizada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materiais_lm', function (Blueprint $table) {
            //
        });
    }
};
