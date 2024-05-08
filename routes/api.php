<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\PedidoController;

// Módulo de Autenticação
Route::prefix("/autenticacao")->group(function () {
    Route::controller(AutenticacaoController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout');
        Route::get('/token', 'verificaToken')->middleware('jwt.auth');
    });
});

// Módulo de Pedidos
Route::prefix("/pedidos")->middleware('jwt.auth')->group(function () {
    Route::controller(PedidoController::class)->group(function () {
        Route::get('/listar-pedidos', 'listarPedidos');
        Route::get('/listar-emival', 'listarEmival');
        Route::get('/filtro-emival', 'listarEmivalFiltro');
        Route::get('/listar-monica', 'listarMonica');
        Route::get('/listar-aprovados', 'listarAprovados');
        Route::get('/listar-reprovados', 'listarReprovados');
        Route::put('/aprovar/{id}', 'aprovarPedido');
        Route::put('/aprovar-ressalva/{id}', 'aprovarRessalva');
        Route::put('/reprovar/{id}', 'reprovarPedido');
        Route::delete('/deletar/{id}', 'deletaPedido');
        Route::post('/cadastrar', 'cadastraPedido');
    });
});

// Módulo de Links
Route::prefix('/links')->middleware('jwt.auth')->group(function () {
    Route::controller(LinkController::class)->group(function () {
        Route::get('/listar-links', 'listarLink');
    });
});

// Módulo de Empresas
Route::prefix('/empresas')->middleware('jwt.auth')->group(function () {
    Route::controller(EmpresaController::class)->group(function () {
        Route::get('/listar-empresas', 'listarEmpresas');
    });
});

// Módulo de Funcionários
Route::prefix('/funcionarios')->middleware('jwt.auth')->group(function () {
    Route::controller(FuncionarioController::class)->group(function () {
        Route::get('/listar-gerentes', 'listarGerentes');
        Route::get('/listar-diretores', 'listarDiretores');
    });
});
