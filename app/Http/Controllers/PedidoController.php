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
}
