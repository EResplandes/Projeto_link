<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StatusService;

class StatusController extends Controller
{

    protected $statusService;

    public function __construct(StatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    public function listarStatus()
    {
        $query = $this->statusService->listarStatus(); // Metódo responsável por buscar todos status
        return response()->json(['resposta' => $query['resposta'], 'itens' => $query['itens']], $query['status']);
    }
}
