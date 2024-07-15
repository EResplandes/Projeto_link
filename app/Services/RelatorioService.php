<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Local;
use App\Models\Pedido;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\PedidosReprovadosResource;
use App\Http\Resources\RelatorioHistoricoPedidosResources;
use App\Models\HistoricoPedidos;

class RelatorioService
{
    public function aprovadosDia($data)
    {
        // 1º Passo -> Buscar todos pedidos com status igual a 4 onde a updated_at é igual a data que foi enviada via parametro
        $query = PedidoResource::collection(
            Pedido::orderBy('created_at', 'desc')
                ->whereDate('updated_at', $data)
                ->where(function ($query) {
                    $query->where('id_status', 4)
                        ->orWhere('id_status', 5);
                })
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu um erro, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function reprovadosDia($dtInicio, $dtFim)
    {
        // Convertendo as datas para o formato Y-m-d se necessário
        $dtInicio = \Carbon\Carbon::parse($dtInicio)->startOfDay();
        $dtFim = \Carbon\Carbon::parse($dtFim)->endOfDay();

        // 1º Passo -> Buscar todos pedidos com status igual a 3 onde a updated_at é igual a data que foi enviada via parametro
        $query = PedidosReprovadosResource::collection(
            Pedido::orderBy('created_at', 'desc')
                ->whereBetween('updated_at', [$dtInicio, $dtFim])
                // ->where(function ($query) {
                //     $query->where('id_status', 3);
                // })
                ->where('id_status', 3)
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu um erro, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function quantidadePedidosPorStatus()
    {
        // 1º Passo -> Executar query
        $quantidadePorStatus = Pedido::join('status', 'pedidos.id_status', '=', 'status.id')
            ->select('status.status', DB::raw('count(*) as total'))
            ->groupBy('status.status')
            ->get();

        $resultado = [];

        // 2º Passo - Inserir os dados no array
        foreach ($quantidadePorStatus as $status) {
            $resultado[] = [
                'status' => $status->status,
                'total' => $status->total,
            ];
        }

        // 3º Passo - Retornar resposta
        return ['resposta' => 'Informações listadas com sucesso!', 'informacoes' => $resultado, 'status' => Response::HTTP_OK];
    }

    public function quantidadePedidosPorStatusPessoa($id)
    {
        // 1º Passo -> Executar query com filtro de id_criador
        $quantidadePorStatus = Pedido::join('status', 'pedidos.id_status', '=', 'status.id')
            ->select('status.status', DB::raw('count(*) as total'))
            ->where('pedidos.id_criador', '=', $id)
            ->groupBy('status.status')
            ->get();

        $resultado = [];

        // 2º Passo - Inserir os dados no array
        foreach ($quantidadePorStatus as $status) {
            $resultado[] = [
                'status' => $status->status,
                'total' => $status->total,
            ];
        }

        // 3º Passo - Retornar resposta
        return ['resposta' => 'Informações listadas com sucesso!', 'informacoes' => $resultado, 'status' => Response::HTTP_OK];
    }

    public function listarHistoricoPedido()
    {
        // 1º Passo -> Buscar pedidos e seu respectivo histórico
        $query = RelatorioHistoricoPedidosResources::collection(HistoricoPedidos::all());

        // 2º Passo -> Retornar resposta
        return ['resposta' => 'Histórico gerado com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
    }
}
