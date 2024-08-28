<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CaixaService;
use App\Http\Requests\CaixaRequest;
use App\Http\Requests\ControleCaixaRequest;

class CaixaController extends Controller
{

    protected $caixaService;

    public function __construct(CaixaService $caixaService)
    {
        $this->caixaService = $caixaService;
    }

    public function listarCaixas()
    {
        $query = $this->caixaService->listar(); // Metódo responsável por listar caixas
        return response()->json(['resposta' => $query['resposta'], 'caixas' => $query['caixas']], $query['status']);
    }

    public function cadastrarCaixa(CaixaRequest $request)
    {
        $query = $this->caixaService->cadastrar($request); // Metódo responsável por cadastrar caixas
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function excluirCaixa($id)
    {
        $query = $this->caixaService->excluir($id); // Metódo responsável por excluir caixas
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function cadastrarControleCaixa(ControleCaixaRequest $request)
    {
        $query = $this->caixaService->cadastrarControleCaixa($request); // Metódo responsável por cadastrar controles de caixas
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function listarControleCaixa($idCaixa, $tipoCaixa)
    {
        $query = $this->caixaService->listarControleCaixa($idCaixa, $tipoCaixa); // Metódo responsável por listar controles de caixas
        return response()->json(['resposta' => $query['resposta'], 'controles' => $query['controles']], $query['status']);
    }
}
