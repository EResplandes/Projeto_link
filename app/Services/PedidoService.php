<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Pedido;
use App\Models\HistoricoPedidos;
use App\Models\Chat;
use App\Http\Resources\PedidoResource;
use Illuminate\Support\Facades\DB;

class PedidoService
{
    public function listar()
    {
        // 1º Passo -> Buscar todos os pedidos cadastrados
        $query = PedidoResource::collection(Pedido::all());

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarEmival()
    {
        // 1ª Passo -> Buscar todos os pedidos com status 1
        $query = PedidoResource::collection(Pedido::where('id_status', 1)->where('id_link', 2)->orderBy('urgente', 'desc')->get());

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos para o Dr. Emival Caiado!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function filtrarEmival($request)
    {
        // 1ª Passo -> Buscar todos os pedidos com status 1
        $query = Pedido::query();
        $query = $query->where('id_status', 1)->where('id_link', 2)->orderBy('urgente', 'desc');

        // 2º Passo -> Verifica se os campos foram passados por url para aplicar filtros
        if ($request->query('empresa')) {
            $query = $query->where('id_empresa', $request->query('empresa'));
        }

        if ($request->query('descricao')) {
            $query = $query->where('descricao', 'LIKE', '%' . $request->query('descricao') . '%');
        }

        if ($request->query('valor')) {
            $query = $query->where('valor', $request->query('valor'));
        }

        if ($request->query('dt_inclusao')) {
            $query = $query->where('created_at', $request->query('dt_inclusao'));
        }

        if ($request->query('urgente')) {
            $query = $query->where('urgente', $request->query('urgente'));
        }

        $query = PedidoResource::collection($query->get()); // Executando consulta

        // 3º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos para o Dr. Emival Caiado!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarMonica()
    {
        // 1ª Passo -> Buscar todos os pedidos com status 2
        $query = PedidoResource::collection(Pedido::where('id_status', 2)->get());

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos para o Dr. Mônica Caiado!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarAprovados()
    {
        // 1º Passo -> Buscar pedidos com status 4
        $query = PedidoResource::collection(Pedido::where('id_status', 4)->get());


        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos aprovados!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarReprovados()
    {
        // 1º Passo -> Buscar pedidos com status 3
        $query = PedidoResource::collection(Pedido::where('id_status', 3)->get());

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos reprovados!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function aprovar($id)
    {

        DB::beginTransaction();

        try {
            // 1º Passo -> Aprovar pedido
            $query = Pedido::where('id', $id)->update(['id_status' => 4]);

            // 2º Passo -> Cadastrar no histórico os dados do pedido
            $dados = [
                'id_pedido' => $id,
                'id_status' => 4,
                'observacao' => 'Pedido aprovado!'
            ];

            // 3º Passo -> Registrando histórico
            $queryHistorico = HistoricoPedidos::create($dados);

            // 4º Passo -> Retornar resposta
            if ($query && $queryHistorico) {
                DB::commit(); //
                return ['resposta' => 'Pedido aprovado com sucesso!', 'status' => Response::HTTP_OK];
            }
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function aprovarRessalva($request, $id)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Aprovar pedido
            $query = Pedido::where('id', $id)->update(['id_status' => 5]);

            // 2º Passo -> Cadastrar no histórico 
            $dados = [
                'id_pedido' => $id,
                'id_status' => 5,
                'observacao' => 'Pedido aprovado com ressalva!'
            ];

            $queryHistorico = HistoricoPedidos::create($dados);

            // 3º Passo -> Iniciar chat com mensagem do Dr. Emival
            $dadosChat = [
                'id_pedido' => $id,
                'id_usuario' => $request->input('id_aprovador'),
                'mensagem' => $request->input('mensagem')
            ];

            $queryChat = Chat::create($dadosChat);

            // 4º Passo -> Retornar resposta
            if ($query && $queryHistorico && $queryChat) {
                DB::commit(); //
                return ['resposta' => 'Pedido aprovado com sucesso!', 'status' => Response::HTTP_OK];
            }

            // 5º Passo -> Voltar pedido para gerente
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function reprovarPedido($request, $id)
    {

        DB::beginTransaction();

        try {
            // 1º Passo -> Reprovar pedido
            $query = Pedido::where('id', $id)->update(['id_status' => 3]);

            // 2º Passo -> Cadastrar no histórico os dados do pedido
            $dados = [
                'id_pedido' => $id,
                'id_status' => 3,
                'observacao' => 'Pedido reprovado!'
            ];

            $queryHistorico = HistoricoPedidos::create($dados);

            // 3º Passo -> Iniciar chat com mensagem do Dr.
            $dadosChat = [
                'id_pedido' => $id,
                'id_usuario' => $request->input('id_aprovador'),
                'mensagem' => $request->input('mensagem')
            ];

            $queryChat = Chat::create($dadosChat);

            // 4º Passo -> Retornar resposta
            if ($query && $queryHistorico && $queryChat) {
                DB::commit(); //
                return ['resposta' => 'Pedido reprovado com sucesso!', 'status' => Response::HTTP_OK];
            }
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function deletaPedido($id)
    {

        DB::beginTransaction();

        try {
            // 1º Passo -> Deletar informações do pedido presentes na tabela historico_pedidos
            $historicoPedidos = HistoricoPedidos::where('id_pedido', $id)->get();

            // Itera sobre os registros e os exclui individualmente
            foreach ($historicoPedidos as $historicoPedido) {
                $historicoPedido->delete();
            }

            // 2º Passo -> Deletar pedido
            $query = Pedido::where('id', $id)
                ->delete();

            // 3º Passo -> Retornar resposta
            if ($query) {
                DB::commit();
                return ['resposta' => 'Pedido excluído com sucesso!', 'status' => Response::HTTP_OK];
            } else {
                return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
            }
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }
}
