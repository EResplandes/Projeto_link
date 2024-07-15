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

    public function reprovadosDia($dtInicio, $dtFim)
    {
        $query = $this->relatorioService->reprovadosDia($dtInicio, $dtFim); // Metódo responsável por buscar pedidos que foram reprovados no dia passado via url
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function quantidadePedidosPorStatus()
    {
        $query = $this->relatorioService->quantidadePedidosPorStatus(); // Metódo responsável por buscar quantidades de pedidos por status
        return response()->json(['resposta' => $query['resposta'], 'informacoes' => $query['informacoes']], $query['status']);
    }

    public function quantidadePedidosPorStatusPessoa($id)
    {
        $query = $this->relatorioService->quantidadePedidosPorStatusPessoa($id); // Metódo responsável por buscar quantidades de pedidos por status
        return response()->json(['resposta' => $query['resposta'], 'informacoes' => $query['informacoes']], $query['status']);
    }

    public function listarHistoricoPedido()
    {
        $query = $this->relatorioService->listarHistoricoPedido(); // Metódo responsável por gerar excel
        // return response()->json(['resposta' => 'Relatório gerado com sucesso!', 'pedidos' => $query['pedidos']], $query['status']);
    }
}
