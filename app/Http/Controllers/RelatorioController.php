<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RelatorioService;

class RelatorioController extends Controller
{
    protected $relatorioService;

    public function __construct(RelatorioService $relatorioService)
    {
        $this->relatorioService = $relatorioService;
    }

    public function aprovadosDia($data)
    {
        $query = $this->relatorioService->aprovadosDia($data); // Metódo responsável por buscar pedidos que foram aprovados de acordo com data enviada por parâmetro
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function reprovadosDia($data)
    {
        $query = $this->relatorioService->reprovadosDia($data); // Metódo responsável por buscar pedidos que foram reprovados no dia passado via url
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

}
