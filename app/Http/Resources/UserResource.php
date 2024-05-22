<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\FuncaoResource;
use App\Http\Resources\GrupoResource;


class UserResource extends JsonResource
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
            "nome"       => $this->name,
            "email"      => $this->email,
            "status"     => $this->status,
            "p_acesso"   => $this->primeiro_acesso,
            "local"      => new LocalResource($this->local),
            "funcao"     => new FuncaoResource($this->funcao),
            "grupo"      => new GrupoResource($this->grupo),
        ];
    }
}
