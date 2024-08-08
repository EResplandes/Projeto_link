<?php

namespace App\Queries;

use App\Models\Pedido;
use App\Models\Fluxo;
use App\Models\HistoricoPedidos;
use Illuminate\Support\Facades\Http;


class PedidosQuery
{
    public function verificaFluxoAprovado($idPedido)
    {
        // 1º  Passo -> Verifica se todo o fluxo referente a esse pedido está assinado
        $query = Fluxo::where('id_pedido', $idPedido[0])
            ->where('assinado', 0)
            ->count();

        // 2º Passo -> Verificar se ainda existe fluxo não aprovado referente a esse pedido
        if ($query > 0) {
            return true;
        } else {
            Pedido::where('id', $idPedido[0])->update(['id_status' => 6]);
            return true;
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
            } else if ($idLink[0] == 3) {
                $atualizaPedido = Pedido::where('id', $idPedido)->update(['id_status' => 22]);

                $dados = [
                    'id_pedido' => $idPedido,
                    'id_status' => 22,
                    'observacao' => 'O pedido foi enviado para Dr. Giovana!'
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

    public function aprovaParcela($id)
    {
        // 1º Passo -> Disparar endpoint
        $req = Http::withHeaders([
            'Authorization' => 'Bearer ' . 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3YxL2F1dGVudGljYWNhby9sb2dpbiIsImlhdCI6MTcxOTUxMDUxNiwiZXhwIjoxNzE5NjkwNTE2LCJuYmYiOjE3MTk1MTA1MTYsImp0aSI6IkZRNlYzY2dBVzJTZVQwekYiLCJzdWIiOiIxIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.tPkCM8B1lA7aG3niSF6MknMS-06jFwg-ee0dzoJ50ck',
        ])->get('https://contratos/api/v1/parcelas/aprova-parcela/' . $id);

        // 2º Passo -> Verificar resposta
        if ($req->successful()) {
            // Processar a resposta da API
            $data = $req->json();

            dd($data);

            return true;
        } else {
            return false;
        }
    }

    public function reprovaParcela($id)
    {
        // 1º Passo -> Disparar endpoint
        $req = Http::withHeaders([
            'Authorization' => 'Bearer ' . 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3YxL2F1dGVudGljYWNhby9sb2dpbiIsImlhdCI6MTcxOTUxMDUxNiwiZXhwIjoxNzE5NjkwNTE2LCJuYmYiOjE3MTk1MTA1MTYsImp0aSI6IkZRNlYzY2dBVzJTZVQwekYiLCJzdWIiOiIxIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.tPkCM8B1lA7aG3niSF6MknMS-06jFwg-ee0dzoJ50ck',
        ])->get('https://contratos/api/v1/parcelas/reprova-parcela/' . $id);

        // 2º Passo -> Verificar resposta
        if ($req->successful()) {
            // Processar a resposta da API
            $data = $req->json();

            dd($data);

            return true;
        } else {
            return false;
        }
    }

    // Backup
    // public function verificaFluxoAprovado($idPedido)
    // {
    //     // 1º  Passo -> Verifica se todo o fluxo referente a esse pedido está assinado
    //     $query = Fluxo::where('id_pedido', $idPedido[0])
    //         ->where('assinado', 0)
    //         ->count();

    //     // 2º Passo -> Verificar para qual link foi enviar o pedido
    //     $idLink = Pedido::where('id', $idPedido[0])
    //         ->pluck('id_link');

    //     // 3º Passo -> Verificar se ainda existe fluxo não aprovado referente a esse pedido
    //     if ($query > 0) {
    //         return true;
    //     } else {

    //         // Mônica
    //         if ($idLink[0] == 1) {
    //             $atualizaPedido = Pedido::where('id', $idPedido[0])->update(['id_status' => 2]);

    //             $dados = [
    //                 'id_pedido' => $idPedido[0],
    //                 'id_status' => 2,
    //                 'observacao' => 'O pedido foi enviado para Dr. Mônica!'
    //             ];

    //             $historico = HistoricoPedidos::create($dados);

    //             return true;
    //         } else {
    //             $atualizaPedido = Pedido::where('id', $idPedido[0])->update(['id_status' => 1]);

    //             $dados = [
    //                 'id_pedido' => $idPedido[0],
    //                 'id_status' => 1,
    //                 'observacao' => 'O pedido foi enviado para Dr. Emival!'
    //             ];

    //             $historico = HistoricoPedidos::create($dados);

    //             return true;
    //         }
    //     }
    // }

    // public function verificaFluxoAprovadoExterno($idPedido)
    // {
    //     // 1º  Passo -> Verifica se todo o fluxo referente a esse pedido está assinado
    //     $query = Fluxo::where('id_pedido', $idPedido)
    //         ->where('assinado', 0)
    //         ->count();

    //     // 2º Passo -> Verificar para qual link foi enviar o pedido
    //     $idLink = Pedido::where('id', $idPedido)
    //         ->pluck('id_link');

    //     // 3º Passo -> Verificar se ainda existe fluxo não aprovado referente a esse pedido
    //     if ($query > 0) {
    //         return true;
    //     } else {

    //         // Mônica
    //         if ($idLink[0] == 1) {
    //             $atualizaPedido = Pedido::where('id', $idPedido)->update(['id_status' => 2]);

    //             $dados = [
    //                 'id_pedido' => $idPedido,
    //                 'id_status' => 2,
    //                 'observacao' => 'O pedido foi enviado para Dr. Mônica!'
    //             ];

    //             $historico = HistoricoPedidos::create($dados);

    //             return true;
    //         } else {
    //             $atualizaPedido = Pedido::where('id', $idPedido)->update(['id_status' => 1]);

    //             $dados = [
    //                 'id_pedido' => $idPedido[0],
    //                 'id_status' => 1,
    //                 'observacao' => 'O pedido foi enviado para Dr. Emival!'
    //             ];

    //             $historico = HistoricoPedidos::create($dados);

    //             return true;
    //         }
    //     }
    // }

}
