<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ParcelaService;

class ParcelaController extends Controller
{
    protected $parcelaService;

    public function __construct(ParcelaService $parcelaService)
    {
        $this->parcelaService = $parcelaService;
    }

    public function cadastrarParcela(Request $request, $id)
    {
        $query = $this->parcelaService->cadastrarParcela($request, $id); // Metódo responsável por cadastrar parcelass
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function buscaParcelas(Request $request)
    {
        $query = $this->parcelaService->buscaParcelas($request); // Metódo responsável por buscar todos pedidos a serem pagos hoje
        return response()->json(['resposta' => $query['resposta'], 'parcelas' => $query['parcelas'], 'total' => $query['total'], 'totalParcelas' => $query['totalParcelas']], $query['status']);
    }

    public function buscaParcelasHoje()
    {
        $query = $this->parcelaService->buscaParcelasHoje(); // Metódo responsável por buscar todos pedidos a serem pagos hoje
        return response()->json(['resposta' => $query['resposta'], 'parcelas' => $query['parcelas'], 'total' => $query['total'], 'totalParcelas' => $query['totalParcelas']], $query['status']);
    }
}
