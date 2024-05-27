<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Local;
use App\Models\Pedido;
use App\Models\HistoricoPedidos;
use App\Models\Chat;
use App\Http\Resources\PedidoResource;

class MonicaService
{
    public function listarQuantidades()
    {
        DB::beginTransaction();

        try {

            // 1º Passo -> Executar as 03 querys de listagem com filtros de preço para Monica

            $quantidades = [];

            $quantidades['qtd_abaixoQuinhentos'] = Pedido::where('id_status', 2)
                ->where('id_link', 1)
                ->where('valor', '<', 500.01) // Filtro para valores abaixo de 500
                ->count();

            $quantidades['qtd_abaixoMil'] = Pedido::where('id_status', 2)
                ->where('id_link', 1)
                ->where('valor', '>', 500.01)
                ->where('valor', '<', 1000.01) // Filtro para valores abaixo de 500
                ->count();


            $quantidades['qtd_acimaMil'] = Pedido::where('id_status', 2)
                ->where('id_link', 1)
                ->where('valor', '>', 1000) // Filtro para valores abaixo de 500
                ->count();

            // 2º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Quantidades listadas com sucesso!', 'quantidades' => $quantidades, 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'quantidade' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function aprovarPedidoAcima($id)
    {

        DB::beginTransaction();

        try {
            // 1º Passo -> Aprovar pedido de acordo com id
            $query = Pedido::find($id);
            $query->id_status = 4;
            $query->save();

            // 2º Passo -> Gerar histórioco retornando id do histórico_gerado para possível delete
            $id_historico = HistoricoPedidos::create([
                'id_pedido' => $id,
                'id_status' => 4,
                'observacao' => 'Pedido aprovado pela Dr. Mônica!'
            ]);

            // 3º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Pedido aprovado com sucesso', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function aprovar($request)
    {
        DB::beginTransaction();

        $pedidosArray = $request['pedidos'];

        try {
            // 1º Passo -> Itera sobre o array de objetos
            foreach ($pedidosArray as $item) {

                $observacao = '';

                switch ($item['status']) {
                    case 3:
                        $observacao = 'O pedido foi reprovado pela Dr. Mônica!';
                        break;
                    case 4:
                        $observacao = 'O pedido foi aprovado pelo Dr. Mônica!';
                        break;
                    case 5:
                        $observacao = 'O pedido foi aprovado com ressalva pelo Dr. Mônica!';
                        break;
                }

                $insertPedido = Pedido::where('id', $item['id'])->update(['id_status' => $item['status']]);

                // Registrando no histórico
                $insertHistorico = HistoricoPedidos::create([
                    'id_pedido' => $item['id'],
                    'id_status' => $item['status'],
                    'observacao' => $observacao
                ]);

                if ($item['status'] != 4) {
                    Chat::create([
                        'id_pedido' => $item['id'],
                        'id_usuario' => 1,
                        'mensagem' => $item['mensagem']
                    ]);
                }
            }

            DB::commit();

            return ['resposta' => 'Pedidos aprovados com sucesso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function listarMonicaMenorQuinhentos()
    {
        // 1ª Passo -> Buscar todos os pedidos com status 1
        $query = PedidoResource::collection(
            Pedido::where('id_status', 2)
                ->where('id_link', 1)
                ->where('valor', '<', 500.01) // Filtro para valores abaixo de 500
                ->orderBy('urgente', 'desc')
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos para o Dr. Mônica Caiado!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarMonicaMenorMil()
    {
        // 1ª Passo -> Buscar todos os pedidos com status 1
        $query = PedidoResource::collection(
            Pedido::where('id_status', 2)
                ->where('id_link', 1)
                ->where('valor', '>', 500.01)
                ->where('valor', '<', 1000.01) // Filtro para valores abaixo de 500
                ->orderBy('urgente', 'desc')
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos para o Dr. Mônica Caiado!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarMonicaMaiorMil()
    {
        // 1ª Passo -> Buscar todos os pedidos com status 1
        $query = PedidoResource::collection(
            Pedido::where('id_status', 2)
                ->where('id_link', 1)
                ->where('valor', '>', 1000) // Filtro para valores abaixo de 500
                ->orderBy('urgente', 'desc')
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos para o Dr. Mônica Caiado!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }
}
