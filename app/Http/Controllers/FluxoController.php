<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FluxoService;

class FluxoController extends Controller
{

    protected $fluxoService;

    public function __construct(FluxoService $fluxoService)
    {
        $this->fluxoService = $fluxoService;
    }

    public function listarFluxo($id)
    {
        $query = $this->fluxoService->listarFluxo($id); // Método responsável por buscar fluxo de pedido
        return response()->json(['resposta' => $query['resposta'], 'fluxo' => $query['fluxo']], $query['status']);
    }

    public function aprovarFluxo($id)
    {
        $query = $this->fluxoService->aprovarFluxo($id); // Método responsável por aprovar fluxo e enciar para aprovação
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function reprovarFluxo($id, $idUsuario, $mensagem)
    {
        $query = $this->fluxoService->reprovarFluxo($id, $idUsuario, $mensagem); // Metódo responsável por reprovar fluxo e enviar devolta para criador do pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function cadastrarFluxo(Request $request)
    {
        $query = $this->fluxoService->cadastrarFluxo($request); // Metódo responsável por cadastrar fluxo
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function verificaFluxo($id_pedido, $id_usuario)
    {
        $query = $this->fluxoService->verificaFluxo($id_pedido, $id_usuario); // Metódo responsável por verificar se fluxo ainda existe
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function indicadores()
    {
        $query = $this->fluxoService->indicadores(); // Metódo responsável por buscar indicadores
        return response()->json(['resposta' => $query['resposta'], 'indicadores' => $query['indicadores']], $query['status']);
    }

    public function aprovarFluxoComRessalva(Request $request)
    {
        $query = $this->fluxoService->aprovarFluxoComRessalva($request); // Metódo responsável por aprovar fluxo e enviar devolta para criador do pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function listarGerentesPedidos($id)
    {
        $query = $this->fluxoService->listarGerentesPedidos($id); // Metódo responsável por buscar pedidos
        return response()->json(['resposta' => $query['resposta'], 'gerentes' => $query['gerentes']], $query['status']);
    }
}
