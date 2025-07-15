<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\BoletosController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ComprovanteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BancoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ExternoController;
use App\Http\Controllers\FluxoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\MonicaController;
use App\Http\Controllers\NotasController;
use App\Http\Controllers\ParcelaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\CaixaController;
use App\Http\Controllers\GerenteController;
use App\Http\Controllers\CotacaoController;
use App\Http\Controllers\DpController;
use App\Http\Controllers\LmController;
use App\Http\Controllers\MetodoController;
use App\Models\Pedido;

// Route::get('/pdf/{id}', function ($id) {
//     $diretorioPdf = Pedido::where('id', $id)->pluck('anexo')->first();

//     $path = storage_path('app/public/' . $diretorioPdf);

//     if (!file_exists($path)) {
//         abort(404);
//     }

//     return response()->file($path);
// });

// Módulo de Autenticação
Route::prefix("/autenticacao")->group(function () {
    Route::controller(AutenticacaoController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout');
        Route::get('/token', 'verificaToken')->middleware('jwt.auth');
        Route::post('/alterar-senha/{id}', 'alterarSenha')->middleware('jwt.auth');
    });
});

// Módulo de Pedidos
Route::prefix("/pedidos")->middleware('jwt.auth')->group(function () {
    Route::controller(PedidoController::class)->group(function () {
        Route::get('/listar-pedidos/{id}', 'listarPedidos'); // ID do local
        Route::get('/listar-pedidos-limitado/{id}/{dtInicio}/{dtFim}', 'listarPedidosLimitados'); // ID do local
        Route::get('/listar-pedidos-por-comprador/{id}', 'listarPedidosPorComprador'); // ID do comprador
        Route::get('/listar-pedidos-por-comprador-status/{id}/{idStatus}', 'listarPedidosPorCompradorStatus'); // ID do comprador
        Route::get('/listar-pedidos-externos', 'listarPedidosExternos');
        Route::get('/listar-pedidos', 'listarTodosPedidosLocais');
        Route::post('/listar-pedidos-filtro', 'listarTodosPedidosLocaisFiltro');
        Route::post('/listar-pedidos-filtro-comprador-externo', 'listarTodosPedidosCompradorExternoFiltro');
        Route::get('/filtro-emival', 'listarEmivalFiltro');
        Route::get('/listar-emival', 'listarEmival');
        Route::get('/listar-emival-gestor-fluxo', 'listarPedidoComEmivalGestorFluxo');
        Route::get('/listar-giovana', 'listarGiovana');
        Route::get('/listar-monica', 'listarMonica');
        Route::get('/listar-aprovados', 'listarAprovados');
        Route::get('/listar-reprovado/{id?}', 'listarReprovados');
        Route::get('/listar-reprovados-fluxo/{id}', 'listarReprovadosFluxo'); // ID do criador
        Route::get('/listar-reprovados-soleni/{id}', 'listarReprovadosSoleni'); // ID do criador
        Route::get('/listar-ressalva/{id?}', 'listarRessalva');
        Route::get('listar-gerente/{id?}', 'listarEmFluxo');
        Route::get('/listar-justificar', 'listarJustificar');
        Route::get('/listar-analise', 'listarAnalise');
        Route::put('/aprovar-ressalva/{id}', 'aprovarRessalva');
        Route::get('/aprovar-fluxo/{id?}/{urgente}', 'aprovarEmFluxo'); // ID do fluxo
        Route::get('/aprovar-fluxo-diretor/{id?}/{idLink?}/{urgente}', 'aprovaEmFluxoDiretor'); // ID do fluxo
        Route::get('/reprovar-fluxo/{id?}/{idUsuario}/{mensagem}', 'reprovarEmFluxo');
        Route::put('/reprovar/{id}', 'reprovarPedido');
        Route::delete('/deletar/{id}', 'deletaPedido');
        Route::post('/cadastrar', 'cadastraPedido');
        Route::post('/cadastrar-sem-fluxo', 'cadastraPedidoSemFluxo');
        Route::post('/cadastrar-nota', 'cadastrarNotaFiscal');
        Route::get('/pedidos-aprovados/{id}', 'listarPedidosAprovados');
        Route::get('/informacoes-pedido/{id}', 'buscaInformacoesPedido'); // ID do Pedido
        Route::get('/informacoes-pedido-alterar/{id}', 'buscaInformacoesPedidoAlterar'); // Id do Pedido
        Route::post('/responde-reprovado/{id}', 'respondeReprovacaoComAnexo');
        Route::post('/responde-reprovado-fluxo/{id}/{idUsuario}/{mensagem}', 'respondeReprovacaoEmFluxo'); // ID do pedido, usuario, mensagem
        Route::post('/responde-reprovado-soleni/{id}', 'respondeReprovacaoSoleni'); // ID do pedido
        Route::post('/responde-reprovado-fiscal/{id}', 'respondeReprovacaoFiscal'); // ID do pedido
        Route::post('/responde-reprovado-financeiro/{id}', 'respondeReprovacaoFinanceiro'); // ID do pedido
        Route::post('/responde-ressalva/{id}', 'respondeRessalvaPedido');
        Route::get('/aprovar-fluxo-externo/{id}/{idLink}/{idUsuario}', 'aprovaEmFluxoExterno'); // ID do pedido e ID do pedido, link e usuario
        Route::get('/reprovar-fluxo-externo/{id}/{idUsuario}/{mensagem}', 'reprovaEmFluxoExterno'); // ID do pedido e ID do pedido, usuario, mensagem
        Route::post('/atualizada-dados-pedido/{id}', 'atualizaDadosPedido'); // ID do Pedido
        Route::get('/listar-pedidos-escriturar', 'listarPedidosEscriturar');
        Route::get('/listar-pedidos-reprovados-fiscal/{id}', 'listarPedidosReprovadosFiscal'); // ID do usuário
        Route::get('/listar-pedidos-financeiro', 'listarPedidosFinanceiro');
        Route::post('/pagar-pedido/{id}', 'pagarPedido'); // ID do Pedido
        Route::post('/reprovar-pedido-financeiro-comprador/{id}', 'reprovarPedidoEnviadoFinanceiroComprador'); // ID do Pedido
        Route::post('/reprovar-pedido-financeiro-fiscal/{id}', 'reprovarPedidoEnviadoFinanceiroFiscal'); // ID do Pedido
        Route::get('/listar-pedidos-reprovados-finaceiro', 'listarReprovadosFinanceiro');
        Route::get('/alterar-urgente/{id}', 'alterarUrgente'); // ID do pedido
        Route::get('/alterar-normal/{id}', 'alterarNormal'); // ID do pedido
        Route::get('/listar-relatorio-emival', 'relatorioEmival');
        Route::get('/listar-controle-financeiro', 'listarControleFinanceiro');
        Route::get('/listar-controle-financeiro-filtro/{idEmpresa}', 'listarControleFinanceiroFiltro'); // ID da empresa
        Route::get('/auditoria-financeiro', 'auditoriaFinanceiro');
        Route::get('/aprovar-giovana/{id}/{idDestino}', 'aprovarGiovana'); // ID do pedido
        Route::post('/aprovar-giovana-pdf', 'aprovarGiovanaPdf'); // ID do pedido
        Route::post('/reprovar-giovana', 'reprovarGiovana');
        Route::get('/listar-pedidos-reprovados-emival/{id}', 'listarReprovadosEmivalGerente'); // ID do usuário (Gerente)
        Route::get('/listar-pedidos-respondidos-para-emival', 'listarPedidosRespondidosParaEmival');
        Route::get('/enviar-pedido-comprador/{id}', 'enviarParaComprador'); // ID do pedido
    });
});

// Rotas Geremte
Route::prefix('/gerente')->group(function () {
    Route::controller(GerenteController::class)->group(function () {
        Route::get('/listar-pedidos-reprovados-emival/{id}', 'listarReprovadosRessalvaEmivalGerente'); // ID do usuário (Gerente)
        Route::post('/responde-mensagem-emival/{idPedido}', 'respoondeMensagemEmival'); // ID do pedido
        Route::get('/buscar-pdf/{id}', 'encontrarPdf');
        Route::post('/aprovar-pedido-gerente', 'aprovarPedidoGerente');
        Route::get('/listar-pedidos/{id}', 'listarTodosPedidosAssociados');
    });
});

// Rotas DP
Route::prefix('/dp')->group(function () {
    Route::controller(DpController::class)->group(function () {
        Route::get('/listar-pedidos', 'listarPedidosDP');
        Route::get('/listar-pedidos-justificar', 'listarPedisoParaJustificar');
        Route::get('/listar-emival-dp', 'listarPedidosEmivalDp');
    });
});

// Rotas App Emival
Route::prefix('/app')->group(function () {
    Route::controller(PedidoController::class)->group(function () {
        Route::get('/listarEmivalMenorQuinhentos', 'listarEmivalMenorQuinhentos');
        Route::get('/listarEmivalMenorMil', 'listarEmivalMenorMil');
        Route::get('/listarEmivalMaiorMil', 'listarEmivalMaiorMil');
        Route::get('/listarPedidosPendentesEmival', 'listarPedidosPendentesEmival');
        Route::get('/listarQuantidades', 'listarQuantidades');
        Route::put('/aprovar-ressalva/{id}', 'aprovarRessalva');
        Route::put('/aprovar', 'aprovarPedido');
        Route::put('/aprovar-monica', 'aprovarMonica');
        Route::put('/aprovar-acima/{id}', 'aprovarPedidoAcima');
        Route::put('/reprovar-acima/{id}/{idUsuario}/{mensagem}', 'reprovarPedidoAcima'); // Id do pedido
        Route::put('/ressalva-acima/{id}/{idUsuario}/{mensagem}', 'aprovarPedidoComRessalvaAcima'); // Id do pedido
        Route::put('/enviar-mensagem/{id}/{mensagem}', 'enviarMensagem'); // Id do pedido
        Route::get('/listar-todos-pedidos-emival-temp', 'listarTodosPedidosEmivalTemp');
        Route::get('/aprovar-pedido-emival-temp/{id}', 'aprovarPedidoEmivalTemp');
        Route::post('/reprovar-pedido-emival-temp', 'reprovarPedidoEmivalTemp');
        Route::post('/ressalva-pedido-emival-temp', 'ressalvaPedidoEmivalTemp');
        Route::post('/cobrar-resposta', 'cobrarResposta');
        Route::post('/finalizar-ressalva', 'finalizarRessalva');
    });
});

// Rotas App Monicaf
Route::prefix('/monica')->group(function () {
    Route::controller(MonicaController::class)->group(function () {
        Route::get('/listarMonicaMenorQuinhentos', 'listarMonicaMenorQuinhentos');
        Route::get('/listarMonicaMenorMil', 'listarMonicaMenorMil');
        Route::get('/listarMonicaMaiorMil', 'listarMonicaMaiorMil');
        Route::get('/listarQuantidades', 'listarQuantidades');
        Route::put('/aprovar-ressalva/{id}', 'aprovarRessalva');
        Route::put('/aprovar', 'aprovarPedido');
        Route::put('/aprovar-acima/{id}', 'aprovarPedidoAcima'); // Id do pedido
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

// Módulo de Metódos de Pagamentos
Route::prefix('/metodos')->middleware('jwt.auth')->group(function () {
    Route::controller(MetodoController::class)->group(function () {
        Route::get('/listar-metodos', 'listarMetodos');
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
        Route::get('/desativa-funcionario/{id?}', 'desativaFuncionario');
        Route::get('/ativa-funcionario/{id?}', 'ativaFuncionario');
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
        Route::get('/listar-conversa/{id?}', 'buscaConversa'); // ID do Pedido
        Route::post('/enviar-mensagem', 'enviarMensagem');
    });
});

// Módulo de Fluxo
Route::prefix('/fluxo')->middleware('jwt.auth')->group(function () {
    Route::controller(FluxoController::class)->group(function () {
        Route::get('/listar-fluxo/{id?}', 'listarFluxo');
        Route::get('/aprovar-fluxo/{id?}', 'aprovarFluxo');
        Route::put('/reprovar-fluxo/{id}/{idUsuario}/{mensagem}', 'reprovarFluxo');
        Route::post('/cadastrar-fluxo', 'cadastrarFluxo');
        Route::get('/verifica-fluxo/{id_pedido}/{id_usuario}', 'verificaFluxo');
        Route::get('/indicadores', 'indicadores');
        Route::post('/aprovar-fluxo-com-ressalva', 'aprovarFluxoComRessalva');
    });
});

// Módulo de Dashboard
Route::prefix('dashboard')->middleware('jwt.auth')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/{id}', 'listarInformacoes');
    });
});

// Módulo de Local
Route::prefix('local')->middleware('jwt.auth')->group(function () {
    Route::controller(LocalController::class)->group(function () {
        Route::get('/listar-locais', 'listarLocais');
    });
});

// Módulo de Relatórios
Route::prefix('relatorios')->middleware('jwt.auth')->group(function () {
    Route::controller(RelatorioController::class)->group(function () {
        Route::get('/listar-locais/{data}', 'aprovadosDia');
        Route::get('listar-reprovados/{dtInicio}/{dtFim}', 'reprovadosDia');
        Route::get('listar-historico-pedido', 'listarHistoricoPedido');
        Route::get('/quantidade-pedidos-por-status', 'quantidadePedidosPorStatus');
        Route::get('/quantidade-pedidos-por-status-pessoal/{id}', 'quantidadePedidosPorStatusPessoa');
    });
});

// Módulo de Notas
Route::prefix('notas')->middleware('jwt.auth')->group(function () {
    Route::controller(NotasController::class)->group(function () {
        Route::post('/cadastrar/{id}', 'cadastrarNota'); // ID do pedido
        Route::post('/cadastrar-somente-nota/{id}', 'cadastrarSomenteNota'); // ID do pedido
        Route::post('/dar-baixa/{id}', 'darBaixaNota'); // ID do pedido
        Route::get('/reprovar/{id}/{mensagem}/{idUsuario}', 'reprovarNota'); // ID do pedido, mensagem e id do usuário
    });
});

// Módulo de Boletos
Route::prefix('boletos')->middleware('jwt.auth')->group(function () {
    Route::controller(BoletosController::class)->group(function () {
        Route::post('/cadastrar/{id}', 'cadastrarBoleto'); // ID do pedido
    });
});

// Módulo de Comprovante
Route::prefix('comprovante')->middleware('jwt.auth')->group(function () {
    Route::controller(ComprovanteController::class)->group(function () {
        Route::post('/abrir-explore', 'abreExplore');
    });
});

// Módulo de parcelas
Route::prefix('parcelas')->middleware('jwt.auth')->group(function () {
    Route::controller(ParcelaController::class)->group(function () {
        Route::post('/cadastrar-parcela/{id}', 'cadastrarParcela'); // ID do pedido
        Route::get('/listar-parcelas-hoje', 'buscaParcelasHoje');
        Route::get('/listar-parcelas-filtradas/{dtInicio}/{dtFim}', 'buscaParcelasFiltradas');
        Route::post('/listar-parcelas', 'buscaParcelas');
        Route::get('/dar-baixa/{id}/{idBanco}', 'darBaixa');
        Route::get('/validar-parcelas/{id}', 'validarParcelas'); // ID do Pedido
        Route::post('/reprovar-parcelas', 'reprovarParcelas');
        Route::post('/alterar-dados-parcela', 'alterarDadosParcela');
        Route::delete('/deletar-parcela/{id}', 'deletarParcela'); // ID da Parcela
        Route::get('/listar-parcelas-por-banco/{id}', 'listarParcelasPorBanco'); // ID do banco
        Route::get('/listar-parcelas-por-pedido/{id}', 'listarParcelasPorPedido'); // ID do pedido
    });
});

// Módulo de Bancos
Route::prefix('bancos')->middleware('jwt.auth')->group(function () {
    Route::controller(BancoController::class)->group(function () {
        Route::get('listar-bancos', 'listarBancos');
    });
});

// Rotas do Sistema de Controle de Pagamentos - SCP
Route::prefix('externo')->middleware('jwt.auth')->group(function () {
    Route::controller(ExternoController::class)->group(function () {
        Route::get('/listar-gerentes', 'listarGerentes');
        Route::get('/listar-diretores', 'listarDiretores');
        Route::get('/listar-presidentes', 'listarPresidentes');
        Route::get('/listar-empresas', 'listarEmpresas');
        Route::get('/listar-locais', 'listarLocais');
        Route::post('/cadastrar-pedido', 'cadastrarPedido');
        Route::get('/consultar-status/{id}', 'consultaStatusPedido');
    });
});

// Módulo de Caixa
Route::prefix('caixas')->middleware('jwt.auth')->group(function () {
    Route::controller(CaixaController::class)->group(function () {
        Route::get('/listar-caixas', 'listarCaixas');
        Route::post('/cadastrar-caixa', 'cadastrarCaixa');
        Route::delete('/excluir-caixa/{id}', 'excluirCaixa');
        Route::post('/filtrar-caixa-por-funcionario', 'filtrarCaixaPorFuncionario');
        Route::get('/listar-controle-caixa/{id}/{tipo}', 'listarControleCaixa');
        Route::post('/cadastrar-fluxo-de-caixa', 'cadastrarFluxoDeCaixa');
        Route::post('/importar-fluxo-de-caixa', 'importarFluxoDeCaixa');
    });
});

// Módulo de Cotações
Route::prefix('cotacoes')->middleware('jwt.auth')->group(function () {
    Route::controller(CotacaoController::class)->group(function () {
        Route::post('/buscar-precos', 'buscarPrecos');
        Route::get('/buscar-cotacoes/{id}', 'buscarCotacoes'); // ID do comprador
        Route::post('/cadastrar-cotacao', 'cadastrarCotacao');
    });
});

// Módulo de Listas de Materias
Route::prefix('lm')->middleware('jwt.auth')->group(function () {
    Route::controller(LmController::class)->group(function () {
        Route::get('/listar-lms', 'listarLms');
        Route::post('/cadastrar-lm', 'cadastrarLm');
        Route::get('/listar-compradores', 'listarCompradores');
        Route::get('/associar-comprador/{idLm}/{idComprador}/{idGerente}', 'associarComprador');
        Route::get('/lm-associadas/{idComprador}', 'lmAssociadas');
        Route::get('/associar-pedido/{idPedido}/{idItem}', 'associarPedido');
        Route::post('/cadastrar-lancamento', 'cadastrarLancamento');
        Route::get('/listar-lancamentos/{idMaterial}', 'listarLancamentos');
        Route::get('/listar-locais', 'listarLocais');
        Route::get('/listar-chat/{idMaterial}', 'listarChat');
        Route::get('/listar-chat-lm/{idLm}', 'listarChatLm');
        Route::post('/enviar-mensagem', 'enviarMensagem');
        Route::post('/enviar-mensagem-lm', 'enviarMensagemLm');
        Route::get('/finalizar-lm/{idLm}', 'finalizarLm');
        Route::get('/iniciar-lm/{idLm}/{idComprador}', 'iniciarLm');
        Route::get('/listar-status-lm', 'listarStatusLm');
        Route::get('alterar-status-lm/{idLm}/{idStatus}/{idComprador}', 'alterarStatusLm');
        Route::get('/listar-status-materiais', 'listarStatusMateriais');
        Route::get('/alterar-status-material/{idMaterial}/{idStatus}/{idComprador}', 'alterarStatusMaterial');
        Route::get('/liberar-material/{idMaterial}/{idComprador}', 'liberarMaterial');
        Route::get('/bloquear-material/{idMaterial}/{idComprador}', 'bloquearMaterial');
        Route::get('/listar-lms-almoxarifado', 'listarLmsAlmoxarifado');
        Route::get('/informacoes-dashboard', 'informacoesDashboard');
        Route::post('/cadastrar-novo-material', 'cadastrarNovoMaterial');
        Route::get('/valida-funcao-usuario/{id}/{funcao}', 'validaFuncaoUsuario');
        Route::get('/listar-anexos/{idLm}', 'listarAnexos');
        Route::post('/salvar-anexo', 'salvarAnexo');
        Route::post('/autorizar-quantidade', 'autorizarQuantidade');
    });
});
