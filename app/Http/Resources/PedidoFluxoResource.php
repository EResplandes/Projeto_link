<?php

namespace App\Http\Resources;

use App\Models\Fluxo;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoFluxoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id"           => $this->id,
            "descricao"    => $this->descricao,
            "valor"        => $this->valor,
            "urgente"      => $this->urgente,
            "anexo"        => $this->anexo,
            "dt_inclusao"  => $this->created_at,
            "empresa"      => new EmpresaResource($this->empresa),
            "status"       => new StatusResource($this->status),
            "link"         => new LinkResource($this->link),
        ];
    }
}
