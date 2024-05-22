<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Fluxo;
use App\Http\Resources\FluxoResource;
use App\Models\HistoricoPedidos;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class FluxoService
{
    public function listarFluxo($id)
    {
        // 1º Passo -> Buscar fluxo do pedido de acordo com id passado
        $query = FluxoResource::collection(Fluxo::where('id_pedido', $id)->get());

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Fluxo listado com sucesso!', 'fluxo' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'fluxo' => 'Erro!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function aprovarFluxo($id)
    {
        // 1º Passo -> Aprovar fluxo mudando status para 7 - Em Fluxo | Tela Soleni
        $query = Pedido::where('id', $id)->update(['id_status' => 7]);

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Fluxo aprovado com sucesso!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function cadastrarFluxo($request)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Montar array a ser inserido
            $dados = [
                'id_pedido' => $request->input('id_pedido'),
                'id_usuario' => $request->input('id_usuario'),
                'assinado' => 0
            ];

            // 2º Passo -> Inserir fluxo
            $query = Fluxo::create($dados);

            // 3º Passo -> Inserir histórico que pedido já foi delegado
            $dadosHistorico = [
                'id_pedido' => $request->input('id_pedido'),
                'id_status' => 9,
                'observacao' => 'Pedido delegado!'
            ];

            HistoricoPedidos::create($dadosHistorico);

            // 4º Passo -> Alterar status do pedido para Em Fluxo (7)
            Pedido::where('id', $dados['id_pedido'])->update(['id_status' => 7]);

            DB::commit();

            // 5º Passo -> Retornar resposta
            return ['resposta' => 'Fluxo cadastrado com sucesso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function verificaFluxo($id_pedido, $id_usuario)
    {
        // 1º Passo -> Verificar se existe na tabela fluxo esse fluxo com a assinatura pendente
        $query = Fluxo::where('id_pedido', $id_pedido)
            ->where('id_usuario', $id_usuario)
            ->where('assinado', 0)
            ->count();

        // 2º Passo -> Pegar id do fluxo para dar baixa quando assinado
        $idFluxo = Fluxo::where('id_pedido', $id_pedido)
            ->where('id_usuario', $id_usuario)
            ->where('assinado', 0)
            ->pluck('id');

        // 3º Passo -> Retornar resposta
        if ($query == 0) {
            return ['resposta' => 'Fluxo inválido!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Fluxo válido!', 'status' => Response::HTTP_OK];
        }
    }
}
