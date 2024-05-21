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

    public function cadastrarFluxo(Request $request)
    {
        $query = $this->fluxoService->cadastrarFluxo($request); // Metódo responsável por cadastrar fluxo
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

}
