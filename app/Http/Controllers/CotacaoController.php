<?php

namespace App\Http\Controllers;

use App\Http\Requests\CotacaoRequest;
use Illuminate\Http\Request;
use App\Services\CotacaoService;

class CotacaoController extends Controller
{

    protected $cotacaoService;

    public function __construct(CotacaoService $cotacaoService)
    {
        $this->cotacaoService = $cotacaoService;
    }

    public function buscarPrecos(Request $request)
    {
        $query = $this->cotacaoService->buscarPrecos($request); // Metódo responsável por buscar materias com api do google shopping
        return response()->json(['resposta' => $query['resposta'], 'resultados' => $query['resultados']], $query['status']);
    }

    public function buscarCotacoes($id)
    {
        $query = $this->cotacaoService->buscarCotacoes($id); // Metódo responsável por buscar todas cotações de 1 devido comprador de acordo com id
        return response()->json(['resposta' => $query['resposta'], 'cotacoes' => $query['cotacoes']], $query['status']);
    }

    public function cadastrarCotacao(CotacaoRequest $request)
    {
        $query = $this->cotacaoService->cadastrarCotacao($request); // Metódo responsável por cadastra cotação
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }
}
