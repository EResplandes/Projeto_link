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
            $table->unsignedBigInteger('id_status')->after('id')->default(1);
            $table->foreign('id_status')->references('id')->on('status_materiais');
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
