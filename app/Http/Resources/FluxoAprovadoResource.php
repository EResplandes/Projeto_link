<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FluxoAprovadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id"                => $this->id,
            "usuario"           => $this->usuario,
            "assinado"          => $this->assinado,
            "data_criacao"      => $this->created_at,
            "data_assinatura"   => $this->updated_at
        ];
    }
}
