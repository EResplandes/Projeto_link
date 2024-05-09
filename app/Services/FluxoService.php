<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Fluxo;
use App\Http\Resources\FluxoResource;

class FluxoService
{
    public function listarFluxo($id)
    {
        // 1º Passo -> Buscar fluxo do pedido de acordo com id passado
        $query = FluxoResource::collection(Fluxo::where('id_pedido', $id)->get());

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Fluxo listado com sucesso!', 'fluxo' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }
}
