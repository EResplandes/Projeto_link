<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoricoLmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray($request)
    {
        return [
            "id"               => $this->id,
            "mensagem"         => $this->observacao,
            "data_observacao"  => $this->created_at,
            "lm"               => new LmSemItensResource($this->lm),
        ];
    }
}
