<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MonicaService;

class MonicaController extends Controller
{

    protected $monicaService;

    public function __construct(MonicaService $monicaService)
    {
        $this->monicaService = $monicaService;
    }

    public function listarQuantidades()
    {
        $query = $this->monicaService->listarQuantidades(); // Metódo responsável por listar todas quantidades que estão com a Dr. Mônica
        return response()->json(['resposta' => $query['resposta'], 'quantidades' => $query['quantidades']], $query['status']);
    }

    public function aprovarPedidoAcima($id)
    {
        $query = $this->monicaService->aprovarPedidoAcima($id); // Metódo responsável por aprovar pedido acima de mil de forma única
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function aprovarPedido($request)
    {
        $query = $this->monicaService->aprovar($request); // Metódo responsável por aprovar pedidos em pacote ou reprovar
        return response()->json(['resposta' => $query['resposta']], $query['resposta']);
    }

    public function listarMonicaMenorQuinhentos()
    {
        $query = $this->monicaService->listarMonicaMenorQuinhentos(); // Metódo responsável por listar pedidos que estão com Dr Monica com valor inferior a 500 reias
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarMonicaMenorMil()
    {
        $query = $this->monicaService->listarMonicaMenorMil(); // Metódo responsável para listar pedidos que estão com Dr Monica que tem o valor abaixo de mil e acima de quinhentos
        return response()->json(['resposta' => $query['resposta'], 'pedidos' => $query['pedidos']], $query['status']);
    }

    public function listarMonicaMaiorMil()
    {
        $query = $this->monicaService->listarMonicaMaiorMil(); // Metódo responsável por listar pedidos acima de 1000 reais
        return response()->json(['resposta' => $query['resposta'], $query['pedidos']], $query['status']);
    }
}
