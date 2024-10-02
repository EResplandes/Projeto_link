<?php

namespace App\Http\Resources;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoricoRespostaPedidosReprovados extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id"         => $this->id,
            "pedido"     => new PedidoAprovadoResource($this->pedido),
            "chat"       => $this->buscaChat($this->id_pedido),
        ];
    }

    protected function buscaChat($id)
    {
        $query = ChatResource::collection(Chat::where('id_pedido', $id)->get());

        return $query;
    }
}
