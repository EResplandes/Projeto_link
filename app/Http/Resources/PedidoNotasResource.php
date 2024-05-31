<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoNotasResource extends JsonResource
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
            "valor"         => $this->valor,
            "protheus"      => $this->protheus,
            "urgente"       => $this->urgente,
            "anexo"         => $this->anexo,
            "dt_inclusao"   => $this->created_at,
            "local"         => $this->local->local,
            "empresa"       => $this->empresa->nome_empresa,
            "status"        => $this->status->status,
        ];
    }
}
