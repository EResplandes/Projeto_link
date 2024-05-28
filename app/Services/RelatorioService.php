<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Local;
use App\Models\Pedido;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\PedidosReprovadosResource;

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

    public function reprovadosDia($data)
    {
        // 1º Passo -> Buscar todos pedidos com status igual a 3 onde a updated_at é igual a data que foi enviada via parametro
        $query = PedidosReprovadosResource::collection(
            Pedido::orderBy('created_at', 'desc')
                ->whereDate('updated_at', $data)
                ->where(function ($query) {
                    $query->where('id_status', 3);
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
}
