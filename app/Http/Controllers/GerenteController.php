<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GerenteService;

class GerenteController extends Controller
{

    protected $gerenteService;

    public function __construct(GerenteService $gerenteService)
    {
        $this->gerenteService = $gerenteService;
    }

    // Metódo reponsável por listar todos pedidos com status 3 onde tem fluxo associado a id do usuário passado
    public function listarReprovadosRessalvaEmivalGerente($id)
    {
        $query = $this->gerenteService->listarReprovadosRessalvaEmivalGerente($id);
        return response()->json([
            'resposta' => $query['resposta'],
            'pedidos_com_fluxo' => $query['pedidos_com_fluxo'],
            'pedidos_sem_fluxo' => $query['pedidos_sem_fluxo'],
            'status' => $query['status']
        ]);
    }

    // Metódo reponsável por contar todos pedidos associados a um gerente para ele acompanhar o fluxo
    public function listarTodosPedidosAssociados($id)
    {
        $query = $this->gerenteService->listarTodosPedidosAssociados($id);
        return response()->json([
            'resposta' => $query['resposta'],
            'pedidos_com_fluxo' => $query['pedidos_com_fluxo'],
            'status' => $query['status']
        ]);
    }

    // Metódo responsável por responder mensagem de Emival e devolver para ele
    public function respoondeMensagemEmival($idPedido, Request $request)
    {
        $query = $this->gerenteService->respoondeMensagemEmival($idPedido, $request);
        return response()->json([
            'resposta' => $query['resposta'],
            'status' => $query['status']
        ]);
    }

    // Metódo responsável por encontrar pdf
    public function encontrarPdf($id)
    {
        $query = $this->gerenteService->encontrarPdf($id);
    }

    public function aprovarPedidoGerente(Request $request)
    {
        $query = $this->gerenteService->aprovarPedidoGerente($request); // Metódo responsável por aprovar pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }
}
