<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\HistoricoPedidos;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\NotasFiscais;
use App\Models\Pedido;

class NotasService
{
    public function cadastrar($request, $id)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Cadastra nota fiscal
            $directory = "/notas"; // Criando diretório

            if ($request->file('nota')) {
                $pdf = $request->file('nota')
                    ->store($directory, 'public'); // Salvando pdf da nota
            } else {
                return ['resposta' => 'O envio da nota é obrigatório!', 'status' => Response::HTTP_BAD_REQUEST];
            }

            // 2º Passo -> Cadastrar na tabela notas o registro
            $dados = [
                'nota' => $pdf,
                'id_pedido' => $id
            ];

            NotasFiscais::create($dados);

            // 3ª Passo -> Alterar status do pedido para enviado para fiscal
            Pedido::where('id', $id)->update(['id_status' => 14]);

            // 4º Passo -> Retornar resposta
            DB::commit();

            return ['resposta' => 'Nota cadastrada com suceso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function darBaixaNota($id)
    {
        DB::beginTransaction();
        // 1º Passo -> Pegar id da nota
        $idNota = NotasFiscais::where('id_pedido', $id)
            ->pluck('id')
            ->first();

        // 2º Passo -> Alterar status da nota
        NotasFiscais::where('id', $idNota)->update(['status' => 'Nota Escriturada']);

        // 3º Passo -> Alterar Status do Pedido
        Pedido::where('id', $id)->update(['id_status' => 15]);

        // 4º Passo -> Retornar resposta
        DB::commit();

        return ['resposta' => 'Nota escriturada e enviada para o Financeiro com sucesso!', 'status' => Response::HTTP_OK];

        try {
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function reprovarNota($id, $mensagem, $idUsuario)
    {
        try {
            // 1º Passo -> Alterar status do pedido para 16 - Nota Reprovada
            Pedido::where('id', $id)->update(['id_status' => 16]);

            // 2º Passo -> Gerar chat
            $dadosChat = [
                'id_pedido' => $id,
                'id_usuario' => $idUsuario,
                'mensagem' => $mensagem
            ];

            Chat::create($dadosChat);

            // 3º Passo -> Gerar histórico
            $dadosHistorico = [
                'id_pedido' => $id,
                'id_status' => 16,
                'observacao' => 'Nota reprovada e enviada para comprador!'
            ];

            HistoricoPedidos::create($dadosHistorico);

            // 4º Passo -> Retornar resposta
            DB::commit();

            return ['resposta' => 'Nota reprovada com sucesso', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }
}
