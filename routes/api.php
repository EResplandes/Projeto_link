<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\FluxoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\StatusController;

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
        Route::get('/filtro-emival', 'listarEmivalFiltro');
        Route::get('/listar-emival', 'listarEmival');
        Route::get('/listar-monica', 'listarMonica');
        Route::get('/listar-aprovados', 'listarAprovados');
        Route::get('/listar-reprovado/{id?}', 'listarReprovados');
        Route::get('listar-gerente/{id?}', 'listarEmFluxo');
        Route::get('/listar-justificar', 'listarJustificar');
        Route::get('/listar-analise', 'listarAnalise');
        Route::put('/aprovar-ressalva/{id}', 'aprovarRessalva');
        Route::get('/aprovar-fluxo/{id?}', 'aprovarEmFluxo');
        Route::put('/reprovar/{id}', 'reprovarPedido');
        Route::delete('/deletar/{id}', 'deletaPedido');
        Route::post('/cadastrar', 'cadastraPedido');
        Route::post('/cadastrar-sem-fluxo', 'cadastraPedidoSemFluxo');
        Route::get('/pedidos-aprovados/{id}', 'listarPedidosAprovados');
        Route::get('/informacoes-pedido/{id}', 'buscaInformacoesPedido');
        Route::post('/responde-reprovado/{id}', 'respondeReprovacaoComAnexo');
    });
});

// Rotas App Emival
Route::prefix('/app')->group(function () {
    Route::controller(PedidoController::class)->group(function () {
        Route::get('/listarEmivalMenorQuinhentos', 'listarEmivalMenorQuinhentos');
        Route::get('/listarEmivalMenorMil', 'listarEmivalMenorMil');
        Route::get('/listarEmivalMaiorMil', 'listarEmivalMaiorMil');
        Route::get('/listarQuantidades', 'listarQuantidades');
        Route::put('/aprovar-ressalva/{id}', 'aprovarRessalva');
        Route::put('/aprovar', 'aprovarPedido');
        Route::put('/aprovar-acima/{id}', 'aprovarPedidoAcima');
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
        Route::post('/cadastrar-empresa', 'cadastrarEmpresa');
        Route::delete('/deletar-empresa/{id}', 'deletarEmpresa');
    });
});

// Módulo de Funcionários
Route::prefix('/funcionarios')->middleware('jwt.auth')->group(function () {
    Route::controller(FuncionarioController::class)->group(function () {
        Route::get('/listar-gerentes', 'listarGerentes');
        Route::get('/listar-diretores', 'listarDiretores');
        Route::get('/listar-responsaveis', 'listarResponsaveis');
        Route::get('/listar-funcionarios', 'listarFuncionarios');
        Route::get('/listar-grupos', 'listarGrupos');
        Route::get('listar-funcoes', 'listarFuncoes');
        Route::post('/cadastrar-funcionario', 'cadastrarFuncionario');
        Route::put('/desativa-funcionario/{id?}', 'desativaFuncionario');
    });
});

// Módulo de Status
Route::prefix('/status')->middleware('jwt.auth')->group(function () {
    Route::controller(StatusController::class)->group(function () {
        Route::get('/listar-status', 'listarStatus');
    });
});

// Módulo de Chat
Route::prefix('/chat')->middleware('jwt.auth')->group(function () {
    Route::controller(ChatController::class)->group(function () {
        Route::get('/listar-conversa/{id?}', 'buscaConversa');
        Route::post('/enviar-mensagem', 'enviarMensagem');
    });
});

// Módulo de Fluxo
Route::prefix('/fluxo')->middleware('jwt.auth')->group(function () {
    Route::controller(FluxoController::class)->group(function () {
        Route::get('/listar-fluxo/{id?}', 'listarFluxo');
        Route::put('/aprovar-fluxo/{id?}', 'aprovarFluxo');
        Route::post('/cadastrar-fluxo', 'cadastrarFluxo');
    });
});

// Módulo de Dashboard
Route::prefix('dashboard')->middleware('jwt.auth')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'listarInformacoes');
    });
});

// Módulo de Local
Route::prefix('local')->middleware('jwt.auth')->group(function () {
    Route::controller(LocalController::class)->group(function () {
        Route::get('/listar-locais', 'listarLocais');
    });
});
