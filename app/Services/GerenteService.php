<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PedidoResource;
use App\Models\Chat;

class GerenteService
{
    public function listarReprovadosRessalvaEmivalGerente($id)
    {
        DB::beginTransaction();

        try {
            $pedidosComFluxo = PedidoResource::collection(Pedido::whereIn('id_status', [3, 5, 24])
                ->whereHas('fluxo', function ($query) use ($id) {
                    $query->where('id_usuario', $id);
                })
                ->get());

            $pedidosSemFluxo = PedidoResource::collection(
                Pedido::whereIn('id_status', [3, 5, 24])
                    ->where('tipo_pedido', 'Sem Fluxo')
                    ->get()
            );

            return [
                'resposta' => 'Pedidos listados com sucesso!',
                'pedidos_com_fluxo' => $pedidosComFluxo,
                'pedidos_sem_fluxo' => $pedidosSemFluxo,
                'status' => Response::HTTP_OK
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'resposta' => 'Erro ao listar pedidos: ' . $e->getMessage(),
                'pedidos_com_fluxo' => null,
                'pedidos_sem_fluxo' => null,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }


    public function respoondeMensagemEmival($idPedido, $request)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Salvar Mensagem
            $dadosMEnsagem = [
                'id_pedido' => $idPedido,
                'id_usuario' => $request->input('id_usuario'),
                'mensagem' => $request->input('mensagem')
            ];

            Chat::create($dadosMEnsagem);

            // 3º Passo -> Alterar status do pedido
            Pedido::where('id', $idPedido)->update(['id_status' => 12]);

            // 4º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Pedido respondido com sucesso!', 'status' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
            throw $e;
        }
    }
}
