<?php

namespace App\Services;

use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Pedido;
use App\Http\Resources\PedidoResource;

class PedidoService
{
    public function listar()
    {
        // 1º Passo -> Buscar todos os pedidos cadastrados
        $query = PedidoResource::collection(Pedido::all());

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }
}
