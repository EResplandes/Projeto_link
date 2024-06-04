<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidosEnviadosFinanceiroResource extends JsonResource
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
            "descricao"     => $this->descricao,
            "dt_vencimento" => $this->dt_vencimento,
            "valor"         => $this->valor,
            "anexo"         => $this->anexo,
            "empresa"       => $this->empresa->nome_empresa,
            "local"         => $this->local->local,
            "status"        => $this->status->status,
            "comprador"     => $this->criador->name,
            "boleto"        => $this->boletos,
            "nota"          => $this->notas
        ];
    }
}
