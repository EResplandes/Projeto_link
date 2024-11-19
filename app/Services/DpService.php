<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PedidoResource;
use App\Models\Pedido;

class DpService
{

    public function listarPedidosDP()
    {
        DB::beginTransaction();

        try {

            $pedidos = PedidoResource::collection(
                Pedido::whereIn('id_criador', [7, 80])
                    ->where('id_status', '!=', 8)
                    ->orderBy('created_at', 'desc')
                    ->get()
            );

            return [
                'resposta' => 'Pedidos listados com sucesso!',
                'pedidos' => $pedidos,
                'status' => Response::HTTP_OK
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'resposta' => 'Erro ao listar pedidos: ' . $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function listarPedisoParaJustificar()
    {
        DB::beginTransaction();

        try {
            $pedidosSemFluxo = PedidoResource::collection(
                Pedido::whereIn('id_status', [3, 5, 24])
                    ->whereIn('id_criador', [7, 80])
                    ->where('tipo_pedido', 'Sem Fluxo')
                    ->get()
            );

            return [
                'resposta' => 'Pedidos listados com sucesso!',
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

    public function listarPedidosEmivalDp()
    {
        // 1ª Passo -> Buscar todos os pedidos que estão com Dr. Emival
        $query = PedidoResource::collection(
            Pedido::where('id_status', 1)
                ->where('id_link', 2)
                ->whereIn('id_criador', [7, 80])
                ->orderBy('urgente', 'desc')
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        }
    }
}
