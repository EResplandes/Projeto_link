<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnexosResource extends JsonResource
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
            "observacao"     => $this->observacao,
            "anexo"          => $this->anexo,
            "extensao"       => $this->extensao,
            "usuario"        => $this->usuario->name,
            "dt_criacao"     => $this->created_at
        ];
    }
}
