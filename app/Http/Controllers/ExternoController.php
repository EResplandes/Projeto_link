<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExternoService;

class ExternoController extends Controller
{

    protected $externoService;

    public function __construct(ExternoService $externoService)
    {
        $this->externoService = $externoService;
    }

    public function listarGerentes()
    {
        $query = $this->externoService->listarGerentes(); // Metódo responsável por listar todos funcionários
        return response()->json(['resposta' => $query['resposta'], 'funcionarios' => $query['funcionarios']], $query['status']);
    }

    public function listarDiretores()
    {
        $query = $this->externoService->listarDiretores(); // Metódo responsável por listar todos funcionários
        return response()->json(['resposta' => $query['resposta'], 'funcionarios' => $query['funcionarios']], $query['status']);
    }

    public function listarPresidentes()
    {
        $query = $this->externoService->listarPresidentes(); // Metódo reponsável por listar responsáveis como gerente e diretores
        return response()->json(['resposta' => $query['resposta'], 'funcionarios' => $query['funcionarios']], $query['status']);
    }

    public function cadastrarPedido(Request $request)
    {
        $query = $this->externoService->cadastrarPedido($request); // Metódo responsável por cadastrar pedido
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }
}
