<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BoletosService;

class BoletosController extends Controller
{

    protected $boletosService;

    public function __construct(BoletosService $boletosService)
    {
        $this->boletosService = $boletosService;
    }

    public function cadastrarBoleto(Request $request, $id)
    {
        $query = $this->boletosService->cadastrar($request, $id); // Metódo responsável por cadastrar boleto
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }
}
