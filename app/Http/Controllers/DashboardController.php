<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function listarInformacoes($id)
    {
        $query = $this->dashboardService->listar($id); // Metódo responsável por buscar todas informações necessarias para montar o dashboard
        return response()->json(['resposta' => $query['resposta'], 'informacoes' => $query['informacoes']], $query['status']);
    }
}
