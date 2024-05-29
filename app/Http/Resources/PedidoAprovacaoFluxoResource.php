<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoAprovacaoFluxoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id"            => $this->id,
            "descricao"     => $this->descricao,
            "dt_inclusao"   => $this->created_at,
            "anexo"         => $this->anexo,
            "empresa"       => $this->empresa->nome_empresa,
            "valor"         => $this->valor,
            "tipo_pedido"   => $this->tipo_pedido,
            "link"          => $this->link->link,
            "criador"       => $this->criador->name,
            "local"         => $this->local->local
        ];
    }
}
