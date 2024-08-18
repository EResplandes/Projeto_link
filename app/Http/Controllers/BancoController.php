<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BancoService;

class BancoController extends Controller
{

    protected $bancoService;

    public function __construct(BancoService $bancoService)
    {
        $this->bancoService = $bancoService;
    }

    public function listarBancos()
    {
        $query = $this->bancoService->listar(); // Metódo responsável por buscar todos bancos
        return response()->json(['resposta' => $query['resposta'], 'bancos' => $query['bancos']], $query['status']);
    }
}
