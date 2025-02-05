<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MetodoService;

class MetodoController extends Controller
{

    protected $metodoService;

    public function __construct(MetodoService $metodoService)
    {
        $this->metodoService = $metodoService;
    }

    public function listarMetodos()
    {
        $query = $this->metodoService->listar(); // Metódo responsável por listar todos os locais
        return response()->json($query);
    }
}
