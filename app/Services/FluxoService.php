<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Fluxo;
use App\Http\Resources\FluxoResource;
use App\Models\Pedido;

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
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'fluxo' => 'Erro!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function aprovarFluxo($id)
    {
        // 1º Passo -> Aprovar fluxo mudando status para 7 - Em Fluxo
        $query = Pedido::where('id', $id)->update(['id_status' => 7]);

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Fluxo aprovado com sucesso!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function reprovaFluxo($id)
    {
        // 1ª Passo ->
    }

}
