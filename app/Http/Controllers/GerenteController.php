<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GerenteService;

class GerenteController extends Controller
{

    protected $gerenteService;

    public function __construct(GerenteService $gerenteService)
    {
        $this->gerenteService = $gerenteService;
    }

    // Metódo reponsável por listar todos pedidos com status 3 onde tem fluxo associado a id do usuário passado

    public function listarReprovadosRessalvaEmivalGerente($id)
    {
        $query = $this->gerenteService->listarReprovadosRessalvaEmivalGerente($id);
        return response()->json([
            'resposta' => $query['resposta'],
            'pedidos_com_fluxo' => $query['pedidos_com_fluxo'],
            'pedidos_sem_fluxo' => $query['pedidos_sem_fluxo'],
            'status' => $query['status']
        ]);
    }
}