<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParcelaPedidoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id_pedido"          => $this->id,
            "descricao"          => $this->descricao,
            "protheus"           => $this->protheus,
            "dt_vencimento_ped"  => $this->dt_vencimento,
            "anexo"              => $this->anexo,
            "status"             => $this->status->status,
            "empresa"            => $this->empresa->nome_empresa,
            "criador"            => $this->criador->name,
            "boleto"             => $this->boletos,
            "nota"               => $this->notas
        ];
    }
}
