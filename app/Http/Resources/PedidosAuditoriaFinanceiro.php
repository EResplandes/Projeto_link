<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\HistoricoPedidos;
use App\Models\NotasFiscais;
use App\Models\Parcela;

class PedidosAuditoriaFinanceiro extends JsonResource
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
            "dt_protheus"           => $this->dt_criacao_pedido,
            "anexo"                 => $this->anexo,
            "status"                => $this->status->status,
            "empresa"               => $this->empresa->nome_empresa,
            "comprador"             => $this->criador->name,
            "local"                 => $this->local,
            "nota"                  => $this->notas,
            "compra_antecipada"     => $this->compra_antecipada,
            "dt_inclusao"           => $this->created_at,
            "dt_aprovacao"          => $this->buscaDtAprovacao($this->id),
            "parcelas"              => $this->parcelas,
            "dt_lancamento_fiscal"  => $this->buscaDtEscrituracao($this->notas ?? null),
            'dt_validacao_finan'    => $this->buscaDtValidacaoFinanceiro($this->id),
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

    public function buscaDtValidacaoFinanceiro($idPedido)
    {
        $query = Parcela::where('id_pedido', $idPedido)->pluck('dt_validacao')->first();

        if ($query) {
            return $query;
        } else {
            return null;
        }
    }

    public function buscaDtEscrituracao($nota)
    {

        if (empty($this->notas)) {
            return null;
        }

        if ($this->notas->isEmpty()) {
            return null;
        }

        if ($nota == null) {
            return null;
        } else {
            $query = NotasFiscais::where('id', $nota[0]->id)
                ->pluck('dt_escrituracao')
                ->first();

            if ($query) {
                return $query;
            } else {
                return null;
            }
        }
    }
}
