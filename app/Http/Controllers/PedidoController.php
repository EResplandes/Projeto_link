<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PedidoService;

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

    public function listarEmival()
    {
        $query = $this->pedidoService->listarEmival(); // Metódo responsável por listar todos pedidos com Emival
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
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

    public function aprovaEmFluxoDiretor($id, $idLink)
    {
        $query = $this->pedidoService->aprovaEmFluxoDiretor($id, $idLink); // Metódo responsável por aprovar pedido que está em fluxo
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function aprovaEmFluxoExterno($id, $idPedido)
    {
        $query = $this->pedidoService->aprovaEmFluxoExterno($id, $idPedido); // Metódo responsável por aprovar pedido que está em fluxo
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

    public function respondeReprovacaoEmFluxo(Request $request, $id)
    {
        $query = $this->pedidoService->respondeReprovacaoEmFluxo($request, $id); // Metódo responsável por responder pedidos reprovados pelo Dr. Emival ou Dr. Monica
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function respondeReprovacaoSoleni(Request $request, $id)
    {
        $query = $this->pedidoService->respondeReprovacaoSoleni($request, $id); // Metódo responsável por responder pedidos reprovados pelo Dr. Emival ou Dr. Monica
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }
}
