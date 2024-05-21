<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Pedido;
use App\Http\Resources\PedidoResource;
use Carbon\Carbon;


class DashboardService
{
    public function listar()
    {
        // Pegando a quantidade de pedidos na centrais
        $qtdCentrais = Pedido::where('id_empresa', 1)->count();

        // Pegando a quantidade de pedidos na centrais
        $qtdCentraisHoje = Pedido::where('id_empresa', 1)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // Pegando a quantidade de pedidos na RTV
        $qtdRtv = Pedido::where('id_empresa', 2)->count();

        // Pegando a quantidade de pedidos na RTV
        $qtdRtvHoje = Pedido::where('id_empresa', 2)
            ->whereDate('created_at', Carbon::today())
            ->count();

        // Pegar últimos pedidos aprovados
        $pedidos = PedidoResource::collection(
            Pedido::orderBy('created_at', 'desc')
                ->where('id_status', 4) // Status Aprovado
                ->limit(10) // Limitando a 10 registros
                ->get()
        );

        // Montando array de informações
        $query = [
            'qtd_centrais_total' => $qtdCentrais,
            'qtd_centrais_hoje' => $qtdCentraisHoje,
            'qtd_rtv_total' => $qtdRtv,
            'qtd_rtv_hoje' => $qtdRtvHoje,
            'ultimos_pedidos' => $pedidos
        ];

        return ['resposta' => 'Informações listadas com sucesso!', 'informacoes' => $query, 'status' => Response::HTTP_OK];
    }
}
