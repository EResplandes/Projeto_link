<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidosComParcelasResource extends JsonResource
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
            "urgente"               => $this->urgente,
            "descricao"             => $this->descricao,
            "protheus"              => $this->protheus,
            "valor"                 => $this->valor,
            "dt_vencimento"         => $this->dt_vencimento,
            "anexo"                 => $this->anexo,
            "status"                => $this->status->status,
            "empresa"               => $this->empresa->nome_empresa,
            "comprador"             => $this->criador->name,
            "local"                 => $this->local,
            "nota"                  => $this->notas,
            "compra_antecipada"     => $this->compra_antecipada,
            "dt_inclusao"           => $this->created_at,
            "total_parcelas"        => $this->parcelas->count(),
            "parcelas_pagas"        => $this->parcelas->where('status', 'Pago')->count(),
            "parcelas"              => $this->parcelas
        ];
    }
}
