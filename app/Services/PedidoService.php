<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Pedido;
use App\Models\HistoricoPedidos;
use App\Models\Chat;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\PedidoFluxoResource;
use App\Http\Resources\FluxoPedidoResource;
use Illuminate\Support\Facades\DB;
use App\Queries\PedidosQuery;
use App\Models\Fluxo;

class PedidoService
{

    protected $pedidosQuery;

    public function __construct(PedidosQuery $pedidosQuery)
    {
        $this->pedidosQuery = $pedidosQuery;
    }

    public function listar()
    {
        // 1º Passo -> Buscar todos os pedidos cadastrados
        $query = PedidoResource::collection(Pedido::orderBy('created_at', 'desc')->get());

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

    public function listarAnalise()
    {
        // 1º Passo -> Buscar pedidos com status 3
        $query = PedidoFluxoResource::collection(Pedido::where('id_status', 6)->get());

        // 3º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos em análise listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
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

            // 4º Passo -> Tirar assinatura do fluxo

            // 5º Passo -> Retornar resposta
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
            // 1º Passo -> Deletar pedido
            $query = Pedido::where('id', $id)
                ->update(['id_status' => 8]);

            // 2º Passo -> Retornar resposta
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

    public function cadastraPedido($request)
    {

        // 1º Passo -> Salvar arquivo e pegar hash gerado
        $directory = "/pedidos"; // Criando diretório

        $pdf = $request->file('anexo')->store($directory, 'public'); // Salvando pdf do pedido

        // 2º Passo -> Montar array a ser inserido
        $urgente = $request->input('urgente') ? 1 : 0;

        $dadosPedido = [
            'descricao' => $request->input('descricao'),
            'valor' => $request->input('valor'),
            'urgente' => $urgente,
            'anexo' => $pdf,
            'id_link' => $request->input('id_link'),
            'id_empresa' => $request->input('id_empresa'),
            'id_status' => 6
        ];

        DB::beginTransaction();

        try {

            // 3º Passo -> Cadastrar pedido com status para soleni
            $queryPedido = Pedido::create($dadosPedido);

            $idPedido = $queryPedido->id;

            // 4º Passo -> Inserir Fluxo
            $fluxoArray = $request->input('fluxo');
            $fluxoArray = json_decode($fluxoArray, true); // Transformando JSON em Array

            // Verificando se array foi preenchido
            if (empty($fluxoArray)) {
                return ['resposta' => 'O fluxo é obrigatório!', 'status' => Response::HTTP_BAD_REQUEST];
            }

            // Insere os itens na tabela fluxos
            if ($fluxoArray != null || $fluxoArray != '') {
                foreach ($fluxoArray as $item) {
                    DB::table('fluxos')->insert([
                        'id_usuario' => $item['id_usuario'],
                        'id_pedido' => $idPedido,
                        'assinado' => 0,
                    ]);
                }
            } else {
                return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
            }

            // 5º Passo -> Cadastrar no historico_pedido
            $dadosHistorico = [
                'id_pedido' => $idPedido,
                'id_status' => 6,
                'observacao' => 'O pedido foi enviado para Análise (SOLENI)!'
            ];

            HistoricoPedidos::create($dadosHistorico); // Inserindo log

            if ($queryPedido) {
                DB::commit();
                return ['resposta' => 'Pedido cadastrado com sucesso!', 'status' => Response::HTTP_CREATED];
            } else {
                return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
            }

            // 5º Passo -> Retornar resposta
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function cadastraPedidoSemFluxo($request)
    {
        // 1º Passo -> Salvar arquivo e pegar hash gerado
        $directory = "/pedidos"; // Criando diretório

        $pdf = $request->file('anexo')->store($directory, 'public'); // Salvando pdf do pedido

        // 2º Passo -> Montar array a ser inserido
        $idLink = $request->input('id_link');
        $idStatus = ($idLink == 2) ? 1 : 2;
        $urgente = $request->input('urgente') ? 1 : 0;

        $dadosPedido = [
            'descricao' => $request->input('descricao'),
            'valor' => $request->input('valor'),
            'urgente' => $urgente,
            'anexo' => $pdf,
            'id_link' => $idLink,
            'id_empresa' => $request->input('id_empresa'),
            'id_status' => $idStatus
        ];

        DB::beginTransaction();

        try {

            // 3º Passo -> Cadastrar pedido
            $queryPedido = Pedido::create($dadosPedido);

            $idPedido = $queryPedido->id;

            // 5º Passo -> Cadastrar no historico_pedido
            $dadosHistorico = [
                'id_pedido' => $idPedido,
                'id_status' => $idStatus,
                'observacao' => 'O pedido foi enviado para o status ' . $idStatus
            ];

            HistoricoPedidos::create($dadosHistorico); // Inserindo log

            if ($queryPedido) {
                DB::commit();
                return ['resposta' => 'Pedido cadastrado com sucesso!', 'status' => Response::HTTP_CREATED];
            } else {
                return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
            }

            // 5º Passo -> Retornar resposta
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function listarEmFluxo($id)
    {
        // 1º Passo -> Buscar pedidos
        $query = FluxoPedidoResource::collection(Fluxo::where('id_usuario', $id)->where('assinado', 0)->get());

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function aprovaEmFluxo($id)
    {
        DB::beginTransaction();

        try {

            // 1ª Passo -> Aprovar pedido de acordo com id_fluxo enviado
            $query = Fluxo::where('id', $id)->update(['assinado' => 1]);

            // 2º Passo -> Pegar id do pedido referente a esse fluxo
            $idPedido = Fluxo::where('id', $id)->pluck('id_pedido');

            // 3º Passo -> Verificar se todo o fluxo referente a esse pedido foi aprovado
            $this->pedidosQuery->verificaFluxoAprovado($idPedido);

            // 4º Passo -> Cadastra histórico
            $dados = [
                'id_pedido' => $idPedido[0],
                'id_status' => 7,
                'observacao' => 'O pedido foi aprovado por um gerente/diretor!'
            ];

            $historico = HistoricoPedidos::create($dados); // Salvando

            // 5º Passo -> Retornar resposta
            if ($query) {
                DB::commit();
                return ['resposta' => 'Pedido aprovado com sucesso!', 'status' => Response::HTTP_OK];
            } else {
                return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
            }
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function reprovarEmFluxo($id)
    {
        // 1º Passo -> Tirar Assinatura da Primeira assinatura mais baixa

    }
}
