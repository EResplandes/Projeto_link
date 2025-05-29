<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LmSemItensResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id"             => $this->id,
            "lm"             => $this->lm,
            "urgente"        => $this->urgente,
            "aplicacao"      => $this->aplicacao,
            "prazo"          => $this->prazo,
            "data_prevista"  => $this->data_prevista,
            "solicitante"    => $this->solicitante->name,
            "comprador"      => $this->comprador?->name,
            "empresa"        => $this->empresa->nome_empresa,
            "status"         => $this->status->status,
            "local"          => $this->local->local,
            "dt_solicitacao" => $this->created_at
        ];
    }
}
