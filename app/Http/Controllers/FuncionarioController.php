<?php

namespace App\Http\Controllers;

use App\Http\Requests\FuncionarioRequest;
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

    public function listarFuncionarios()
    {
        $query = $this->funcionarioService->listarFuncionarios(); // Busca todos usuários
        return response()->json(['resposta' => $query['resposta'], 'usuarios' => $query['usuarios']], $query['status']);
    }

    public function listarGrupos()
    {
        $query = $this->funcionarioService->listarGrupos(); // Metódo responsável por listar grupos
        return response()->json(['resposta' => $query['resposta'], 'grupos' => $query['grupos']], $query['status']);
    }

    public function listarFuncoes()
    {
        $query = $this->funcionarioService->listarFuncoes(); // Metódo responsável por listar funções
        return response()->json(['resposta' => $query['resposta'], 'funcoes' => $query['funcoes']], $query['status']);
    }

    public function cadastrarFuncionario(FuncionarioRequest $request)
    {
        $query = $this->funcionarioService->cadastrar($request); // Metódo responsável por cadastrar funcinpario/usuário
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }
}
