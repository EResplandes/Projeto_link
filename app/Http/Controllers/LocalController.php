<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LocalService;

class LocalController extends Controller
{

    protected $localService;

    public function __construct(LocalService $localService)
    {
        $this->localService = $localService;
    }

    public function listarLocais()
    {
        $query = $this->localService->listar(); // Metódo responsável por listar todos locais
        return response()->json(['resposta' => $query['resposta'], 'locais' => $query['locais']], $query['status']);
    }
}
