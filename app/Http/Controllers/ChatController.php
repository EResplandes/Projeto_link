<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatService;

class ChatController extends Controller
{

    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function buscaConversa($id)
    {
        $query = $this->chatService->buscaConversa($id); // Metódo responsável por buscar conversas
        return  response()->json(['resposta' => $query['resposta'], 'conversa' => $query['conversa']], $query['status']);
    }
}
