<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Chat;
use App\Http\Resources\ChatResource;
use App\Models\Fluxo;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatService
{
    public function buscaConversa($id)
    {
        // 1º Passo -> Buscar todos registro da conversa de acordo com o id do pedido
        $query = ChatResource::collection(Chat::where('id_pedido', $id)->get());

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Chat listado com sucesso!', 'conversa' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function enviarMensagem(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1ª Passo -> Montar array a ser inserido
            $dados = [
                'id_pedido' => $request->input('id_pedido'),
                'id_usuario' => $request->input('id_usuario'),
                'mensagem' => $request->input('mensagem')
            ];

            // 2º Passo -> Cadastrar mensagem
            Chat::create($dados);

            // 3º Passo -> Verificar na tabela fluxo qual fluxo representa esse chat para deixar ele como assinado
            Fluxo::where('id_pedido', $dados['id_pedido'])
                ->where('id_usuario', $dados['id_usuario'])
                ->where('assinado', 0)
                ->update(['assinado' => 1]);

            // 4º Passo -> Editar status do pedido verificando para qual link devo enviar
            $linkParaEnvio = Pedido::where('id', $dados['id_pedido'])->pluck('id_link');

            // Verifica se é para Mônica
            if ($linkParaEnvio[0] == 1) {
                Pedido::where('id', $dados['id_pedido'])->update(['id_status' => 2]);
            } else {
                Pedido::where('id', $dados['id_pedido'])->update(['id_status' => 1]);
            }

            // 5º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Mensagem enviada com sucesso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }
}
