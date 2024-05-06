<?php

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
Route::prefix("/pedidos")->middleware('jwt.auth')->group(function () {
    Route::controller(PedidoController::class)->group(function () {
        Route::get('/listar-pedidos', 'listarPedidos');
        Route::get('/listar-emival', 'listarEmival');
        Route::get('/listar-monica', 'listarMonica');
        Route::get('/listar-aprovados', 'listarAprovados');
        Route::get('/listar-reprovados', 'listarReprovados');
        Route::put('/aprovar/{id}', 'aprovarPedido');
        Route::put('/aprovar-ressalva/{id}', 'aprovarRessalva');
        Route::put('/reprovar/{id}', 'reprovarPedido');
        Route::delete('/deletar/{id}', 'deletaPedido');
    });
});
