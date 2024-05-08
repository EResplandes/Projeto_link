<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmpresaService;

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
}
