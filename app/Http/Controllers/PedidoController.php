<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PedidoService;

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

    public function listarEmival()
    {
        $query = $this->pedidoService->listarEmival(); // Metódo responsável por listar pedidos com status 1
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
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

    public function aprovarPedido($id)
    {
        $query = $this->pedidoService->aprovar($id); // Metódo responsável por aprovar pedidos
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
}
