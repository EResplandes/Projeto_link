<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoResource extends JsonResource
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
            "descricao"  => $this->descricao,
            "valor"      => $this->valor,
            "urgente"    => $this->urgente,
            "anexo"      => $this->anexo,
            "empresa"    => new EmpresaResource($this->id_empresa),
            "status"     => new StatusResource($this->id_status),
            "link"       => new LinkResource($this->id_link),
        ];
    }
}