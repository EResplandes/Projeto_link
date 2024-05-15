<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PedidoService;
use App\Http\Requests\PedidoRequest;

class PedidoController extends Controller
{

    protected $pedidoService;

    public function __construct(PedidoService $pedidoService)
    {
        $this->pedidoService = $pedidoService;
    }

    public function listarPedidos()
    {
        $query = $this->pedidoService->listar(); // Metódo responsável por listar pedidos
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

    public function listarReprovados()
    {
        $query = $this->pedidoService->listarReprovados(); // Metódo responsável por listar pedidos com status 3
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarAnalise()
    {
        $query = $this->pedidoService->listarAnalise(); // Metódo responsável por listar pedidos com status 6
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
}
