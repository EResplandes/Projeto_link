<?php

namespace App\Queries;

use App\Models\User;
use Illuminate\Http\Response;
use App\Models\Pedido;
use App\Models\Fluxo;
use App\Models\HistoricoPedidos;

class PedidosQuery
{
    public function verificaFluxoAprovado($idPedido)
    {
        // 1º  Passo -> Verifica se todo o fluxo referente a esse pedido está assinado
        $query = Fluxo::where('id_pedido', $idPedido[0])
            ->where('assinado', 0)
            ->count();

        // 2º Passo -> Verificar para qual link foi enviar o pedido
        $idLink = Pedido::where('id', $idPedido[0])
            ->pluck('id_link');

        // 3º Passo -> Verificar se ainda existe fluxo não aprovado referente a esse pedido
        if ($query > 0) {
            return true;
        } else {

            // Mônica
            if ($idLink[0] == 1) {
                $atualizaPedido = Pedido::where('id', $idPedido[0])->update(['id_status' => 2]);

                $dados = [
                    'id_pedido' => $idPedido[0],
                    'id_status' => 2,
                    'observacao' => 'O pedido foi enviado para Dr. Mônica!'
                ];

                $historico = HistoricoPedidos::create($dados);

                return true;
            } else {
                $atualizaPedido = Pedido::where('id', $idPedido[0])->update(['id_status' => 1]);

                $dados = [
                    'id_pedido' => $idPedido[0],
                    'id_status' => 1,
                    'observacao' => 'O pedido foi enviado para Dr. Emival!'
                ];

                $historico = HistoricoPedidos::create($dados);

                return true;
            }
        }
    }

    public function verificaFluxoAprovadoExterno($idPedido)
    {
        // 1º  Passo -> Verifica se todo o fluxo referente a esse pedido está assinado
        $query = Fluxo::where('id_pedido', $idPedido)
            ->where('assinado', 0)
            ->count();

        // 2º Passo -> Verificar para qual link foi enviar o pedido
        $idLink = Pedido::where('id', $idPedido)
            ->pluck('id_link');

        // 3º Passo -> Verificar se ainda existe fluxo não aprovado referente a esse pedido
        if ($query > 0) {
            return true;
        } else {

            // Mônica
            if ($idLink[0] == 1) {
                $atualizaPedido = Pedido::where('id', $idPedido)->update(['id_status' => 2]);

                $dados = [
                    'id_pedido' => $idPedido,
                    'id_status' => 2,
                    'observacao' => 'O pedido foi enviado para Dr. Mônica!'
                ];

                $historico = HistoricoPedidos::create($dados);

                return true;
            } else {
                $atualizaPedido = Pedido::where('id', $idPedido)->update(['id_status' => 1]);

                $dados = [
                    'id_pedido' => $idPedido[0],
                    'id_status' => 1,
                    'observacao' => 'O pedido foi enviado para Dr. Emival!'
                ];

                $historico = HistoricoPedidos::create($dados);

                return true;
            }
        }
    }
}
