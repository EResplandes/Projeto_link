<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PedidosReprovadosResource extends JsonResource
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
            "descricao"         => $this->descricao,
            "valor"             => $this->valor,
            "dt_vencimento"     => $this->formataData($this->dt_vencimento),
            "presidente"        => $this->link->link,
            "status"            => $this->status->status,
            "empresa"           => $this->empresa->nome_empresa,
            "criador"           => $this->criador->name,
            "local"             => $this->local->local,
            "dt_reprovacao"     => $this->formataData($this->updated_at),
            "mensagem"          => $this->chat
        ];
    }

    public function formataData($data)
    {
        $date = Carbon::parse($data);
        return $date->format('d/m/Y H:i:s');
    }
}
