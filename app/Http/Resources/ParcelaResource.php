<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParcelaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id_parcela"         => $this->id,
            "valor_parcela"      => $this->valor,
            "dt_vencimento"      => $this->dt_vencimento,
            "status"             => $this->status,
            "pedido"             => new ParcelaPedidoResource($this->pedido)
        ];
    }
}
