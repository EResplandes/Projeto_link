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
        Schema::table('pedidos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_criador')->nullable()->after('id_empresa');
            $table->enum('tipo_pedido', ['Com Fluxo', 'Sem fluxo'])->after('dt_vencimento')->default('SeM fluxo');
            $table->foreign('id_criador')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            //
        });
    }
};
