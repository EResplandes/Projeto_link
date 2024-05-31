<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id_nota"            => $this->id,
            "nota"               => $this->nota,
            "dt_inclusao_nota"   => $this->created_at,
            "pedido"             => new PedidoNotasResource($this->pedidos)
        ];
    }
}
