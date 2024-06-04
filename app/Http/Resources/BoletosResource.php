<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoletosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id_boleto"          => $this->id,
            "boleto"             => $this->boleto,
            "dt_inclusao_boleto" => $this->created_at,
            "pedido"             => new PedidoNotasResource($this->pedidos)
        ];
    }
}
