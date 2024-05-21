<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FluxoResource extends JsonResource
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
            "funcao"            => $this->usuario->funcao,
            "assinado"          => $this->assinado,
            "data_criacao"      => $this->created_at
        ];
    }
}
