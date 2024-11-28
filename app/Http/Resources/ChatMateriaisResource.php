<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMateriaisResource extends JsonResource
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
            "usuario"           => $this->usuario->name,
            "mensagem"          => $this->mensagem,
            "data_mensagem"     => $this->created_at
        ];
    }
}
