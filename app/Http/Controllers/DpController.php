<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DpService;

class DpController extends Controller
{

    protected $dpService;

    public function __construct(DpService $dpService)
    {
        $this->dpService = $dpService;
    }

    public function listarPedidosDP()
    {
        $query = $this->dpService->listarPedidosDP(); // Metódo responsável por listar os pedidos
        return response()->json($query);
    }

    public function listarPedisoParaJustificar()
    {
        $query = $this->dpService->listarPedisoParaJustificar(); // Metódo responsável por listar pedidos para justificar
        return response()->json($query);
    }
}
