<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Chat;
use App\Http\Resources\ChatResource;

class ChatService
{
    public function buscaConversa($id)
    {
        // 1º Passo -> Buscar todos registro da conversa de acordo com o id do pedido
        $query = ChatResource::collection(Chat::where('id_pedido', $id)->get());

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Chat listado com sucesso!', 'conversa' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }
}
