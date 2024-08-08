<?php

namespace App\Services;

use App\Models\HistoricoPedidos;
use Illuminate\Http\Response;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function listar($id)
    {

        DB::beginTransaction();

        try {
            // Pegando quantidade de pedidos aprovados
            $qtdPedidosAprovados = Pedido::where('id_status', 4)
                ->where('id_criador', $id)
                ->count();

            // Pegando quantidade de pedidos reprovados pela soleni
            $qtdPedidosReprovadosSoleni = Pedido::where('id_status', 11)
                ->where('id_criador', $id)
                ->count();

            // Pegando quantidade de pedidos reprovados por Emival
            $qtdPedidosReprovadosEmival = Pedido::where('id_status', 3)
                ->where('id_criador', $id)
                ->count();

            // Pegando quantidade de pedidos reprovados por Gerente ou Diretor
            $qtdPedidosReprovadosGerenteDiretor = Pedido::where('id_status', 10)
                ->where('id_criador', $id)
                ->count();

            // Executar query com filtro de id_criador
            $quantidadePorStatus = Pedido::join('status', 'pedidos.id_status', '=', 'status.id')
                ->select('status.status', DB::raw('count(*) as total'))
                ->where('pedidos.id_criador', '=', $id)
                ->groupBy('status.status')
                ->get();

            $informacoes = [
                'qtdPedidosAprovados' => $qtdPedidosAprovados,
                'qtdPedidosReprovadosSoleni' => $qtdPedidosReprovadosSoleni,
                'qtdPedidosReprovadosEmival' => $qtdPedidosReprovadosEmival,
                'qtdPedidosReprovadosGerenteDiretor' => $qtdPedidosReprovadosGerenteDiretor,
                'pedidosPorStatus' => $quantidadePorStatus,
            ];

            return ['resposta' => 'Informações listadas com sucesso!', 'informacoes' => $informacoes, 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'informacoes' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }
}
