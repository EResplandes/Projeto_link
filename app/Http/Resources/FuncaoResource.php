<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FuncaoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id"                    => $this->id,
            "funcao"                => $this->name,
            "superior"              => $this->superior,
            "inferior"              => $this->inferior,
            'dt_criacao_usuario'    => $this->created_at
        ];
    }
}
