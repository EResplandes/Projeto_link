<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CotacoesResource extends JsonResource
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
            "finalidade"   => $this->finalidade,
            "rm"           => $this->rm,
            "comprador"    => new UserResource($this->comprador),
            "empresa"      => new EmpresaResource($this->empresa),
            "local"        => new LocalResource($this->local),
            "pedido"       => $this->pedido,
            "dt_criacao"   => $this->created_at
        ];
    }
}
