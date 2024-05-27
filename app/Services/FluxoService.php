<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Fluxo;
use App\Http\Resources\FluxoResource;
use App\Models\HistoricoPedidos;
use App\Models\Pedido;
use App\Models\Chat;
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

        DB::beginTransaction();

        try {

            // 1ª Passo -> Descobrindo qual tipo a função é se é sem fluxo ou com fluxo
            $verificaTipoPedido = trim(Pedido::where('id', $id)->pluck('tipo_pedido')->first());

            // Usa comparação case-insensitive
            if (strcasecmp($verificaTipoPedido, "Com fluxo") === 0) {
                // Atualiza o status do pedido
                Pedido::where('id', $id)->update(['id_status' => 7]);
                // Confirma a operação
                DB::commit();

                // Retorna resposta
                return ['resposta' => 'Fluxo aprovado com sucesso!', 'status' => Response::HTTP_OK];
            } else {
                // Obtém o ID do link
                $idLink = Pedido::where('id', $id)->pluck('id_link')->first();

                if ($idLink == 1) {
                    // Atualiza o status do pedido
                    Pedido::where('id', $id)->update(['id_status' => 2]);

                    // Dados para o histórico
                    $dados = [
                        'id_pedido' => $id,
                        'id_status' => 2,
                        'observacao' => 'O pedido foi enviado para Dr. Mônica!'
                    ];
                } else {
                    // Atualiza o status do pedido
                    Pedido::where('id', $id)->update(['id_status' => 1]);

                    // Dados para o histórico
                    $dados = [
                        'id_pedido' => $id,
                        'id_status' => 1,
                        'observacao' => 'O pedido foi enviado para Dr. Emival!'
                    ];
                }

                // Cria o histórico do pedido
                HistoricoPedidos::create($dados);

                // Confirma a operação
                DB::commit();

                // Retorna resposta
                return ['resposta' => 'Fluxo aprovado com sucesso!', 'status' => Response::HTTP_OK];
            }
        } catch (\Exception $e) {
            // Desfaz a transação em caso de erro
            DB::rollback();

            // Retorna a resposta de erro
            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function reprovarFluxo($id, $idUsuario, $mensagem)
    {

        DB::beginTransaction();

        try {
            // 1º Passo -> Reprovar o pedido e colocar o status 11
            Pedido::where('id', $id)->update(['id_status' => 11]);

            // 2º Passo -> Inserir mensagem o pq o pedido foi reprovado
            $dadosChat = [
                'id_pedido' => $id,
                'id_usuario' => $idUsuario,
                'mensagem' => $mensagem
            ];

            Chat::create($dadosChat);

            // 3º Passo -> Retornar resposta

            DB::commit();
            return ['resposta' => 'Pedido reprovado com sucesso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
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
