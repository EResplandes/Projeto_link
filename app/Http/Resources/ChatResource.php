<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            "id_pedido"         => $this->id_pedido,
            "id_usuario"        => $this->usuario,
            "mensagem"          => $this->mensagem,
            "data_mensagem"     => $this->created_at
        ];
    }
}
