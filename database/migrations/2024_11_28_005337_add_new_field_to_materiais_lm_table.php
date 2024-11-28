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
            $table->boolean('liberado_almoxarife')->default('0')->after('id_pedido');
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
