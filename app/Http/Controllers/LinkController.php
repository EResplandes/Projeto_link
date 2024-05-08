<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LinkService;

class LinkController extends Controller
{

    protected $linkService;

    public function __construct(LinkService $linkService)
    {
        $this->linkService = $linkService;
    }

    public function listarLink()
    {
        $query = $this->linkService->listar(); // Metódo responsável por listar links
        return response()->json(['resposta' => $query['resposta'], 'links' => $query['links']], $query['status']);
    }
}
