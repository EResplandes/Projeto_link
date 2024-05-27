<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FluxoPedidoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id_fluxo"          => $this->id,
            "pedido"            => new PedidoFluxoResource($this->pedido),
            "usuario"           => $this->usuario,
            "assinado"          => $this->assinado,
            "data_criacao"      => $this->created_at
        ];
    }
}
