<?php

namespace App\Http\Controllers;

use App\Services\EmpresaService;
use App\Http\Requests\EmpresaRequest;

class EmpresaController extends Controller
{

    protected $empresaService;

    public function __construct(EmpresaService $empresaService)
    {
        $this->empresaService = $empresaService;
    }

    public function listarEmpresas()
    {
        $query = $this->empresaService->listar(); // Metódo responsável por listar empresas
        return response()->json(['resposta' => $query['resposta'], 'empresas' => $query['empresas']], $query['status']);
    }

    public function cadastrarEmpresa(EmpresaRequest $request)
    {
        $query = $this->empresaService->cadastrar($request); // Metódo responsável por cadastrar empresa
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }

    public function deletarEmpresa($id)
    {
        $query = $this->empresaService->deletar($id); // Metódo responsável por deletar empresa
        return response()->json(['resposta' => $query['resposta']], $query['status']);
    }
}
