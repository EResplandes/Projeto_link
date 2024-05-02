<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\PedidoController;

// Módulo de Autenticação
Route::prefix("/autenticacao")->group(function () {
    Route::controller(AutenticacaoController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout');
    });
});

// Módulo de Pedidos
Route::prefix("/pedidos")->group(function () {
    Route::controller(PedidoController::class)->group(function () {
        Route::get('/listar-pedidos', 'listarPedidos');
    });
});
