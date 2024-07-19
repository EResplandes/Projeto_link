<?php

namespace App\Http\Resources;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PedidoResource extends JsonResource
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
            "valor"         => $this->valor,
            "protheus"      => $this->protheus,
            "urgente"       => $this->urgente,
            "anexo"         => $this->anexo,
            "dt_inclusao"   => $this->created_at,
            "dt_assinatura" => $this->updated_at,
            "local"         => $this->local,
            "criador"       => $this->criador->name,
            "nota"                  => $this->notas,
            "compra_antecipada"     => $this->compra_antecipada,
            "empresa"       => new EmpresaResource($this->empresa),
            "status"        => new StatusResource($this->status),
            "link"          => new LinkResource($this->link),
            "pendentes"     => $this->getPendentesComNomeUsuario(),
            "assinados"     => $this->getAssinadosComNomeUsuario(),
            "verifica_chat" => $this->checkChatRecordForUser($this->id)
        ];
    }

    /**
     * Obtém os itens pendentes com o nome do usuário associado.
     *
     * @return array
     */
    protected function getPendentesComNomeUsuario()
    {
        return $this->fluxo->where('assinado', 0)->map(function ($item) {
            return [
                'id' => $item->id,
                'nome_usuario' => $item->usuario->name,
                'funcao' => $item->usuario->funcao->funcao
            ];
        })->values()->all();
    }

    /**
     * Obtém os itens pendentes com o nome do usuário associado.
     *
     * @return array
     */
    protected function getAssinadosComNomeUsuario()
    {
        return $this->fluxo->where('assinado', 1)->map(function ($item) {
            return [
                'id' => $item->id,
                'data_assinatura' => $item->updated_at,
                'nome_usuario' => $item->usuario->name,
                'funcao' => $item->usuario->funcao->funcao
            ];
        })->values()->all();
    }

    protected function checkChatRecordForUser($idPedido)
    {
        return Chat::where('id_pedido', $idPedido)
            ->whereIn('id_usuario', [1, 3])
            ->exists();
    }
}
