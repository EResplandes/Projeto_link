<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoInformacoesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            "id"            => $this->id,
            "urgente"       => $this->urgente,
            "descricao"     => $this->descricao,
            "protheus"      => $this->protheus,
            "valor"         => $this->valor,
            "dt_vencimento" => $this->dt_vencimento,
            "anexo"         => $this->anexo,
            "empresa"       => $this->empresa
        ];
    }
}
