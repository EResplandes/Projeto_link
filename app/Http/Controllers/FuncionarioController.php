<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FuncionarioService;

class FuncionarioController extends Controller
{

    protected $funcionarioService;

    public function __construct(FuncionarioService $funcionarioService)
    {
        $this->funcionarioService = $funcionarioService;
    }

    public function listarGerentes()
    {
        $query = $this->funcionarioService->listarGerentes(); // Metódo responsável por listar todos funcionários
        return response()->json(['resposta' => $query['resposta'], 'funcionarios' => $query['funcionarios']], $query['status']);
    }

    public function listarDiretores()
    {
        $query = $this->funcionarioService->listarDiretores(); // Metódo responsável por listar todos funcionários
        return response()->json(['resposta' => $query['resposta'], 'funcionarios' => $query['funcionarios']], $query['status']);
    }
}
