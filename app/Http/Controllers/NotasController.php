<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotasService;


class NotasController extends Controller
{

    protected $notasService;

    public function __construct(NotasService $notasService)
    {
        $this->notasService = $notasService;
    }

    public function cadastrarNota(Request $request, $id)
    {
        $query = $this->notasService->cadastrar($request, $id); // Metódo responsável por cadastrar nota e boleto
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function cadastrarSomenteNota(Request $request, $id)
    {
        $query = $this->notasService->cadastrarSomenteNota($request, $id); // Metódo responsável por cadastrar somente nota
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function darBaixaNota(Request $request, $id)
    {
        $query = $this->notasService->darBaixaNota($id, $request); // Metódo responsável por dar baixa na nora
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function reprovarNota($id, $mensagem, $idUsuario)
    {
        $query = $this->notasService->reprovarNota($id, $mensagem, $idUsuario); // Metódo responsável por reprovar nota
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }
}
