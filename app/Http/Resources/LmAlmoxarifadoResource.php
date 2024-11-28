<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LmAlmoxarifadoResource extends JsonResource
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
            "lm"             => $this->lm,
            "urgente"        => $this->urgente,
            "aplicacao"      => $this->aplicacao,
            "prazo"          => $this->prazo,
            "data_prevista"  => $this->data_prevista,
            "solicitante"    => $this->solicitante->name,
            "comprador"      => $this->comprador?->name,
            "empresa"        => $this->empresa->nome_empresa,
            "status"         => $this->status->status,
            "local"          => $this->local->local,
            "materiais"      => $this->materiais
                ->filter(function ($material) {
                    return $material->liberado_almoxarife == 1; // Filtra materiais liberados
                })
                ->map(function ($material) {
                    return [
                        "id"               => $material->id,
                        "descricao"        => $material->descricao,
                        "quantidade"       => $material->quantidade,
                        "unidade"          => $material->unidade,
                        "id_pedido"           => $material->id_pedido,
                        "id_status"           => $material->id_status,
                        "liberado_almoxarife" => $material->liberado_almoxarife,
                        "quantidade_entregue" => $material->lancamentosMateriais()->sum('quantidade_entregue'), // Soma os lançamentos
                    ];
                }),
            "dt_solicitacao" => $this->created_at
        ];
    }
}
