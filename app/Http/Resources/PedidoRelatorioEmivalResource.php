<?php

namespace App\Http\Resources;

use App\Models\HistoricoPedidos;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use DateTime;

class PedidoRelatorioEmivalResource extends JsonResource
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
            "dt_aprovacao"          => $this->buscaDtAprovacao($this->id),
        ];
    }

    public function buscaDtAprovacao($id)
    {
        $aprovacao = HistoricoPedidos::where('id_pedido', $id)->where('id_status', 4)->exists();

        if ($aprovacao) {
            return HistoricoPedidos::where('id_pedido', $id)
                ->where('id_status', 4)
                ->pluck('created_at')
                ->first();
        } else {
            return null;
        }
    }
}
