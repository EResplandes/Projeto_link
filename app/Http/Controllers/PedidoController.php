<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PedidoService;
use Illuminate\Http\Client\ResponseSequence;

use function PHPUnit\Framework\returnSelf;

class PedidoController extends Controller
{

    protected $pedidoService;

    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }

    public function listarPedidos($id)
    {
        $query = $this->pedidoService->listar($id); // Metódo responsável por listar pedidos
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarPedidosLimitados($id, $dtInicio, $dtFim)
    {
        $query = $this->pedidoService->listarPedidosLimitados($id, $dtInicio, $dtFim); // Método responsável por buscar pedidos com limite
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarPedidosPorComprador($id)
    {
        $query = $this->pedidoService->listarPedidosPorComprador($id); // Metódo responsável por listar pedidos
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarPedidosPorCompradorStatus($id, $idStatus)
    {
        $query = $this->pedidoService->listarPedidosPorCompradorStatus($id, $idStatus); // Metódo responsável por listar pedidos
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarPedidosExternos()
    {
        $query = $this->pedidoService->listarTodosExternos(); // Metódo responsável por listar pedidos
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarTodosPedidosLocais()
    {
        $query = $this->pedidoService->listarTodosLocais(); // Metódo responsável por listar pedidos
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarTodosPedidosLocaisFiltro(Request $request)
    {
        $query = $this->pedidoService->listarTodosLocaisFiltro($request); // Metódo responsável por listar pedidos com filtro
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarEmival()
    {
        $query = $this->pedidoService->listarEmival(); // Metódo responsável por listar todos pedidos com Emival
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarPedidoComEmivalGestorFluxo()
    {
        $query = $this->pedidoService->listarPedidoComEmivalGestorFluxo(); // Metódo responsável por listar todos pedidos com Emival
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarGiovana()
    {
        $query = $this->pedidoService->listarGiovana(); // Metódo responsável por listar pedidos com Dr Giovana - status 22
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function aprovarGiovana($id, $idDestino)
    {
        $query = $this->pedidoService->aprovarGiovana($id, $idDestino); // Metódo responsável por aprovar pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function aprovarGiovanaPdf(Request $request)
    {
        $query = $this->pedidoService->aprovarGiovanaPdf($request); // Metódo responsável por aprovar pdf
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function reprovarGiovana(Request $request)
    {
        $query = $this->pedidoService->reprovarGiovana($request); // Metódo responsável por reprovar pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function listarEmivalMenorQuinhentos()
    {
        $query = $this->pedidoService->listarEmivalMenorQuinhentos(); // Metódo responsável por listar pedidos com status 1 e valor abaixo de 500
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarEmivalMenorMil()
    {
        $query = $this->pedidoService->listarEmivalMenorMil(); // Metódo responsável por listar pedidos com status 1 e valor entre 500,01 e 1000
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarEmivalMaiorMil()
    {
        $query = $this->pedidoService->listarEmivalMaiorMil(); // Metódo responsável por listar pedidos com status 1 e valor maior que 1000
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarEmivalFiltro(Request $request)
    {
        $query = $this->pedidoService->filtrarEmival($request); // Metódo responsável por listar pedidos com status 1 e filtros
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos'], $query['status']]);
    }

    public function listarMonica()
    {
        $query = $this->pedidoService->listarMonica(); // Metódo responsável por listar pedidos com status 2
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarAprovados()
    {
        $query = $this->pedidoService->listarAprovados(); // Metódo responsável por listar pedidos com status 4
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarReprovados($id)
    {
        $query = $this->pedidoService->listarReprovados($id); // Metódo responsável por listar pedidos com status 3
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarAnalise()
    {
        $query = $this->pedidoService->listarAnalise(); // Metódo responsável por listar pedidos com status 6
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarJustificar(Request $request)
    {
        $query = $this->pedidoService->listarJustificar($request); // Metódo responsável por listar todos pedidos onde foi reprovado
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function aprovarPedido(Request $request)
    {
        $query = $this->pedidoService->aprovar($request); // Metódo responsável por aprovar pedidos
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function aprovarMonica(Request $request)
    {
        $query = $this->pedidoService->aprovarMonica($request); // Metódo responsável por aprovar pedidos
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function aprovarRessalva(Request $request, $id)
    {
        $query = $this->pedidoService->aprovarRessalva($request, $id); // Metódo responsável por aprovar com ressalva
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function reprovarPedido(Request $request, $id)
    {
        $query = $this->pedidoService->reprovarPedido($request, $id); // Metódo responsável por reprovar pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function deletaPedido($id)
    {
        $query = $this->pedidoService->deletaPedido($id); // Metódo responsável por deletar pedido de acordo com id
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function cadastraPedido(Request $request)
    {
        $query = $this->pedidoService->cadastraPedido($request); // Metódo responsável por cadastrar pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function cadastraPedidoSemFluxo(Request $request)
    {
        $query = $this->pedidoService->cadastraPedidoSemFluxo($request); // Metódo responsável por cadastrar pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function cadastrarNotaFiscal(Request $request)
    {
        $query = $this->pedidoService->cadastrarNotaFiscal($request); // Metódo responsável por cadastrar nota
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function listarEmFluxo($id)
    {
        $query = $this->pedidoService->listarEmFluxo($id); // Metódo responsável por listar pedidos em fluxo
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function aprovarEmFluxo($id)
    {
        $query = $this->pedidoService->aprovaEmFluxo($id); // Metódo responsável por aprovar pedido que está em fluxo
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function aprovaEmFluxoDiretor($id, $idLink, $urgente)
    {
        $query = $this->pedidoService->aprovaEmFluxoDiretor($id, $idLink, $urgente); // Metódo responsável por aprovar pedido que está em fluxo
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function aprovaEmFluxoExterno($id, $idLink, $idUsuario)
    {
        $query = $this->pedidoService->aprovaEmFluxoExterno($id, $idLink, $idUsuario); // Metódo responsável por aprovar pedido que está em fluxo
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function reprovaEmFluxoExterno($id, $idUsuario, $mensagem)
    {
        $query = $this->pedidoService->reprovaEmFluxoExterno($id, $idUsuario, $mensagem); // Metódo reponsável por reprovar pedido em fluxo
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function reprovarEmFluxo($id, $idUsuario, $mensagem)
    {
        $query = $this->pedidoService->reprovarEmFluxo($id, $idUsuario, $mensagem); // Metódo responsável por reprovar pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function aprovarPedidoAcima($id)
    {
        $query = $this->pedidoService->aprovarPedidoAcima($id); // Metódo responsável por aprovar pedido separado
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function reprovarPedidoAcima($id, $idUsuario, $mensagem)
    {
        $query = $this->pedidoService->reprovarPedidoAcima($id, $mensagem, $idUsuario); // Metódo responsável por reprovar pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function aprovarPedidoComRessalvaAcima($id, $idUsuario, $mensagem)
    {
        $query = $this->pedidoService->aprovarPedidoComRessalvaAcima($id, $idUsuario, $mensagem); // Metódo responsável por aprovar com ressalva
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function listarQuantidades()
    {
        $query = $this->pedidoService->listarQuantidades(); // Metódo responsável por listar quantidades de cada tipo de pedido
        return response()->json(['resposta' => $query['resposta'], 'quantidades' => $query['quantidades']], $query['status']);
    }

    public function listarPedidosAprovados($id)
    {
        $query = $this->pedidoService->listarPedidosAprovados($id); // Metódo responsável por listar pedidos aprovados de acordo com id do usupario que criou
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function buscaInformacoesPedido($id)
    {
        $query = $this->pedidoService->buscaInformacoesPedido($id); // Metódo responsável por buscar informações de um pedido de acordo com id do pedido
        return response()->json(['resposta' => $query['resposta'], 'pedido' => $query['pedido'], 'informacoes' => $query['informacoes']], $query['status']);
    }

    public function buscaInformacoesPedidoAlterar($id)
    {
        $query = $this->pedidoService->buscaInformacoesPedidoAlterar($id); // Metódo responsável por buscar informações de um pedido de acordo com id do pedido
        return response()->json(['resposta' => $query['resposta'], 'pedido' => $query['pedido']], $query['status']);
    }

    public function respondeReprovacaoComAnexo(Request $request, $id)
    {
        $query = $this->pedidoService->respondeReprovacaoComAnexo($request, $id); // Metódo responsável por responder pedidos reprovados pelo Dr. Emival ou Dr. Monica
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function listarRessalva($id)
    {
        $query = $this->pedidoService->listarRessalva($id); // Metódo responsável por listar pedidos aprovados com ressalva de acordo com id do criador
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function respondeRessalvaPedido(Request $request, $id)
    {
        $query = $this->pedidoService->respondeRessalvaPedido($request, $id); // Metódo responsável por responder e enviar novamente pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function listarReprovadosFluxo($id)
    {
        $query = $this->pedidoService->listarReprovadosFluxo($id); // Metódo reponsável por listar todos pedidos com status 10 de acordo com id do usuário logado
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarReprovadosSoleni($id)
    {
        $query = $this->pedidoService->listarReprovadosSoleni($id); // Metódo reponsável por listar todos pedidos com status 10 de acordo com id do usuário logado
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function respondeReprovacaoEmFluxo(Request $request, $id, $idUsuario, $mensagem)
    {
        $query = $this->pedidoService->respondeReprovacaoEmFluxo($request, $id, $idUsuario, $mensagem); // Metódo responsável por responder pedidos reprovados pelo Dr. Emival ou Dr. Monica
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function respondeReprovacaoSoleni(Request $request, $id)
    {
        $query = $this->pedidoService->respondeReprovacaoSoleni($request, $id); // Metódo responsável por responder pedidos reprovados pelo Dr. Emival ou Dr. Monica
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function respondeReprovacaoFinanceiro(Request $request, $id)
    {
        $query = $this->pedidoService->respondeReprovacaoFinanceiro($request, $id); // Metódo responsável por responder pedidos reprovados por Financeiro
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function respondeReprovacaoFiscal(Request $request, $id)
    {
        $query = $this->pedidoService->respondeReprovacaoFiscal($request, $id); // Metódo responsável por responder pedidos reprovados pelo Dr. Emival ou Dr. Monica
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function atualizaDadosPedido(Request $request, $id)
    {
        $query = $this->pedidoService->atualizaDadosPedido($request, $id); // Metódo responsável por alterar dados do pedido de acordo com ID passado via url
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function listarPedidosEscriturar()
    {
        $query = $this->pedidoService->listarPedidosEscriturar(); // Metódo responsável por listar pedidos com status 14
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarPedidosReprovadosFiscal($id)
    {
        $query = $this->pedidoService->listarPedidosReprovadosFiscal($id); // Metódo responsável por listar pedidos reprovados pelo fiscal
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarPedidosFinanceiro()
    {
        $query = $this->pedidoService->listarPedidosFinanceiro(); // Metódo responsável por listar todos pedidos com status 15
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function pagarPedido(Request $request, $id)
    {
        $query = $this->pedidoService->pagarPedido($request, $id); // Metódo responsável por pagar pedido enviado para comprador inserir nota ou para finalizar processo
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function reprovarPedidoEnviadoFinanceiroComprador(Request $request, $id)
    {
        $query = $this->pedidoService->reprovarPedidoEnviadoFinanceiroComprador($request, $id); // Metódo responsável por reprovar pedido e voltar para comprador justificar ou alterar
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function reprovarPedidoEnviadoFinanceiroFiscal(Request $request, $id)
    {
        $query = $this->pedidoService->reprovarPedidoEnviadoFinanceiroFiscal($request, $id); // Metódo responsável por reprovar pedido e voltar para fiscal tomar devidas providencias
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function listarReprovadosFinanceiro()
    {
        $query = $this->pedidoService->listarReprovadosFinanceiro(); // Metódo responsável por listar todos pedidos reprovados pelo financeiro
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function alterarUrgente($id)
    {
        $query = $this->pedidoService->alterarUrgente($id); // Metódo responsável por alterar se pedido é urgente ou não pela SOLENI
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function alterarNormal($id)
    {
        $query = $this->pedidoService->alterarNormal($id); // Metódo responsável por alterar se pedido é urgente ou não pela SOLENI
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function relatorioEmival()
    {
        $query = $this->pedidoService->relatorioEmival(); // Metódo repsonsável por buscar todos pedidos com dt_emissao, dt_inclusao e dt_aprovacao
        return response()->json(['resposta' => $query['resposta'], 'totalPedidos' => $query['totalPedidos'], 'totalValor' => $query['totalValor'], 'pedidos' => $query['pedidos'],], $query['status']);
    }

    public function listarControleFinanceiro()
    {
        $query = $this->pedidoService->listarControleFinanceiro(); // Metódo responsável por buscar todos pedidos que contém parcelas
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarControleFinanceiroFiltro($idEmpresa)
    {
        $query = $this->pedidoService->listarControleFinanceiroFiltro($idEmpresa); // Metódo responsável por buscar todos pedidos que contém parcelas com filtro por empresa
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function auditoriaFinanceiro()
    {
        $query = $this->pedidoService->auditoriaFinanceiro(); // Metódo responsável por auditória do financeiro
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarTodosPedidosCompradorExternoFiltro(Request $request)
    {
        $query = $this->pedidoService->listarTodosPedidosCompradorExternoFiltro($request); // Metódo responsável por buscar todos pedidos de comprador externo com filtros
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarPedidosRespondidosParaEmival()
    {
        $query = $this->pedidoService->listarPedidosRespondidosParaEmival(); // Metódo responsável por buscar todos pedidos respondidos para Emival
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function enviarParaComprador($id)
    {
        $query = $this->pedidoService->enviarParaComprador($id); // Metódo responsável por enviar pedido para comprador justificar
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function enviarMensagem($id, $mensagem)
    {
        $query = $this->pedidoService->enviarMensagem($id, $mensagem); // Metódo responsável por enviar mensagem sem aprovar nem reprovar pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function listarTodosPedidosEmivalTemp()
    {
        $query = $this->pedidoService->listarTodosPedidosEmivalTemp();
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function aprovarPedidoEmivalTemp($id)
    {
        $query = $this->pedidoService->aprovarPedidoEmivalTemp($id);
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function reprovarPedidoEmivalTemp(Request $request)
    {
        $query = $this->pedidoService->reprovarPedidoEmivalTemp($request);
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function ressalvaPedidoEmivalTemp(Request $request)
    {
        $query = $this->pedidoService->ressalvaPedidoEmivalTemp($request);
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function listarPedidosPendentesEmival()
    {
        $query = $this->pedidoService->listarPedidosPendentesEmival();
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function cobrarResposta(Request $request)
    {
        $query = $this->pedidoService->cobrarResposta($request);
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function finalizarRessalva(Request $request)
    {
        $query = $this->pedidoService->finalizarRessalva($request);
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }
}
