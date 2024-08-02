<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Pedido;
use App\Models\HistoricoPedidos;
use App\Models\Chat;
use App\Http\Resources\PedidoResource;
use App\Http\Resources\PedidoFluxoResource;
use App\Http\Resources\FluxoPedidoResource;
use App\Http\Resources\FluxoAprovadoResource;
use App\Http\Resources\PedidoAprovadoResource;
use App\Http\Resources\PedidoInformacoesResource;
use App\Http\Resources\PedidoRelatorioEmivalResource;
use App\Http\Resources\NotasResource;
use App\Http\Resources\PedidoAprovacaoFluxoResource;
use App\Http\Resources\PedidosAuditoriaFinanceiro;
use App\Http\Resources\PedidosComParcelasResource;
use App\Http\Resources\PedidosEnviadosFinanceiroResource;
use App\Models\Boleto;
use Illuminate\Support\Facades\DB;
use App\Queries\PedidosQuery;
use App\Models\Fluxo;
use App\Models\NotasFiscais;
use App\Models\Parcela;
use Carbon\Carbon;


class PedidoService
{

    protected $pedidosQuery;

    public function __construct(PedidosQuery $pedidosQuery)
    {
        $this->pedidosQuery = $pedidosQuery;
    }

    public function listar($id)
    {
        // 1º Passo -> Buscar todos os pedidos cadastrados
        $query = PedidoResource::collection(
            Pedido::orderBy('created_at', 'desc')
                ->where('id_local', $id)
                ->take(500)
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarPedidosLimitados($id, $dtInicio, $dtFim)
    {

        // Convertendo as datas para o formato Y-m-d se necessário
        $dtInicio = \Carbon\Carbon::parse($dtInicio)->startOfDay();
        $dtFim = \Carbon\Carbon::parse($dtFim)->endOfDay();

        // 1º Passo -> Buscar todos os pedidos cadastrados
        $query = PedidoResource::collection(
            Pedido::orderBy('created_at', 'desc')
                ->where('id_local', $id)
                ->whereBetween('created_at', [$dtInicio, $dtFim])
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return [
                'resposta' => 'Pedidos listados com sucesso!',
                'pedidos' => $query,
                'status' => Response::HTTP_OK
            ];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarPedidosPorComprador($id)
    {
        // 1º Passo -> Buscar todos os pedidos cadastrados
        $query = PedidoResource::collection(
            Pedido::orderBy('created_at', 'desc')
                ->where('id_criador', $id)
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarTodosExternos()
    {
        // 1º Passo -> Buscar todos os pedidos cadastrados
        $query = PedidoResource::collection(
            Pedido::orderBy('created_at', 'desc')
                ->whereIn('id_local', [2, 3]) // Busca pedidos com IDs 2 e 3
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarTodosLocais()
    {
        // 1º Passo -> Buscar todos os pedidos cadastrados
        $query = PedidoResource::collection(
            Pedido::orderBy('created_at', 'desc')
                ->take(1500)
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarJustificar($request)
    {
        /* Pegar todos pedidos com status de Em fluxo no caso 6 onde não estão assinados e que tem registro na tabela historico_pedidos com status 9 */

        // 1º Passo -> Pegar pedidos com necessidade de justificativa
        $fluxo = Fluxo::where('id_usuario', $request->query('id_usuario'))
            ->where('assinado', 0)
            ->get();

        $idsPedidos = $fluxo->pluck('id_pedido')->all();

        $pedidosValidos = HistoricoPedidos::whereIn('id_pedido', $idsPedidos)
            ->where('id_status', 9)
            ->pluck('id_pedido')
            ->all();

        // Filtra os pedidos originais com base na verificação
        $pedidosFiltrados = $fluxo->filter(function ($pedido) use ($pedidosValidos) {
            return in_array($pedido->id_pedido, $pedidosValidos);
        });

        // Coleta os dados completos dos pedidos válidos
        $resultados = $pedidosFiltrados->map(function ($pedido) {
            return new PedidoResource(Pedido::where('id', $pedido->id_pedido)->orderBy('created_at', 'desc')->first());
        });

        // Converte a coleção de pedidos em uma matriz
        $pedidosArray = $resultados->values()->all();

        return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $pedidosArray, 'status' => Response::HTTP_OK];
    }

    public function listarEmival()
    {
        // 1ª Passo -> Buscar todos os pedidos que estão com Dr. Emival
        $query = PedidoResource::collection(
            Pedido::where('id_status', 1)
                ->where('id_link', 2)
                ->orderBy('urgente', 'desc')
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        }
    }

    public function listarEmivalMenorQuinhentos()
    {
        // 1ª Passo -> Buscar todos os pedidos com status 1
        $query = PedidoResource::collection(
            Pedido::whereIn('id_status', [1, 12]) // Substitua [1, 2, 3] pelos valores desejados
                ->where('id_link', 2)
                ->where('valor', '<', 500.01) // Filtro para valores abaixo de 500
                ->orderBy('urgente', 'desc')
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos para o Dr. Emival Caiado!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarEmivalMenorMil()
    {
        // 1ª Passo -> Buscar todos os pedidos com status 1
        $query = PedidoResource::collection(
            Pedido::whereIn('id_status', [1, 12]) // Substitua [1, 2, 3] pelos valores desejados
                ->where('id_link', 2)
                ->where('valor', '>', 500.01)
                ->where('valor', '<', 1000.01) // Filtro para valores abaixo de 500
                ->orderBy('urgente', 'desc')
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos para o Dr. Emival Caiado!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarEmivalMaiorMil()
    {
        // 1ª Passo -> Buscar todos os pedidos com status 1
        $query = PedidoResource::collection(
            Pedido::whereIn('id_status', [1, 12]) // Substitua [1, 2, 3] pelos valores desejados
                ->where('id_link', 2)
                ->where('valor', '>', 1000) // Filtro para valores abaixo de 500
                ->orderBy('urgente', 'desc')
                ->get()
        );

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
        $query = PedidoResource::collection(
            Pedido::whereIn('id_status', [2, 12])
                ->where('id_link', 1)
                ->get()
        );

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

    public function listarReprovados($id)
    {
        // 1º Passo -> Buscar pedidos com status 3
        $query = PedidoResource::collection(
            Pedido::where('id_status', 3)
                ->where('id_local', $id)
                ->get()
        );

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
        $query = PedidoAprovacaoFluxoResource::collection(Pedido::where('id_status', 6)->get());

        // 3º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos em análise listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function aprovar($request)
    {
        DB::beginTransaction();

        $pedidosArray = $request['pedidos'];
        // $pedidosArray = json_decode($pedidosArray, true); // Transformando JSON em Array

        try {
            // 1º Passo -> Itera sobre o array de objetos
            foreach ($pedidosArray as $item) {

                $observacao = '';

                $statusAtual = Pedido::where('id', $item['id'])->pluck('id_status')->first();

                switch ($item['status']) {
                    case 3:
                        $observacao = 'O pedido foi reprovado pelo Dr. Emival!';
                        break;
                    case 4:
                        $observacao = 'O pedido foi aprovado pelo Dr. Emival!';
                        break;
                    case 5:
                        $observacao = 'O pedido foi aprovado com ressalva pelo Dr. Emival!';
                        break;
                }

                $dadosHistorico = [''];

                if ($statusAtual == 12 && $item['status'] == 3) {
                    Pedido::where('id', $item['id'])->update(['id_status' => 13]);

                    $dadosHistorico = [
                        'id_pedido' => $item['id'],
                        'id_status' => 13,
                        'observacao' => $observacao
                    ];
                } else {

                    $compraAntecipada = Pedido::where('id', $item['id'])->pluck('compra_antecipada')->first();

                    if ($compraAntecipada == 'Sim' && $observacao != 'O pedido foi reprovado pelo Dr. Emival!') {
                        Pedido::where('id', $item['id'])->update(['id_status' => 14]);
                    } else {
                        Pedido::where('id', $item['id'])->update(['id_status' => $item['status']]);
                    }

                    $dadosHistorico = [
                        'id_pedido' => $item['id'],
                        'id_status' => $item['status'],
                        'observacao' => $observacao
                    ];
                }

                HistoricoPedidos::create($dadosHistorico);

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

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function aprovarMonica($request)
    {
        DB::beginTransaction();

        $pedidosArray = $request['pedidos'];

        try {
            // 1º Passo -> Itera sobre o array de objetos
            foreach ($pedidosArray as $item) {

                $observacao = '';

                $statusAtual = Pedido::where('id', $item['id'])->pluck('id_status')->first();

                switch ($item['status']) {
                    case 3:
                        $observacao = 'O pedido foi reprovado pelo Dra. Mônica!';
                        break;
                    case 4:
                        $observacao = 'O pedido foi aprovado pelo Dra. Mônica!';
                        break;
                    case 5:
                        $observacao = 'O pedido foi aprovado com ressalva pelo Dra. Mônica!';
                        break;
                }

                $dadosHistorico = [''];

                if ($statusAtual == 12 && $item['status'] == 3) {
                    Pedido::where('id', $item['id'])->update(['id_status' => 13]);

                    $dadosHistorico = [
                        'id_pedido' => $item['id'],
                        'id_status' => 13,
                        'observacao' => $observacao
                    ];
                } else {
                    Pedido::where('id', $item['id'])->update(['id_status' => $item['status']]);

                    $dadosHistorico = [
                        'id_pedido' => $item['id'],
                        'id_status' => $item['status'],
                        'observacao' => $observacao
                    ];
                }

                HistoricoPedidos::create($dadosHistorico);

                if ($item['status'] != 4) {
                    Chat::create([
                        'id_pedido' => $item['id'],
                        'id_usuario' => 3,
                        'mensagem' => $item['mensagem']
                    ]);
                }
            }

            DB::commit();

            return ['resposta' => 'Pedidos aprovados com sucesso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

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
        $dadosPedido = [
            'descricao' => $request->input('descricao'),
            'valor' => $request->input('valor'),
            'urgente' => $request->input('urgente'),
            'dt_vencimento' => $request->input('dt_vencimento'),
            'dt_criacao_pedido' => $request->input('dt_criacao_pedido'),
            'anexo' => $pdf,
            'id_link' => $request->input('id_link'),
            'id_empresa' => $request->input('id_empresa'),
            'id_status' => 7,
            'id_criador' => $request->input('id_criador'),
            'id_local' => $request->input('id_local'),
            'protheus' => intval($request->input('protheus')),
            'tipo_pedido' => 'Com Fluxo',
        ];

        DB::beginTransaction();

        try {

            // 3º Passo -> Cadastrar pedido com status para soleni
            if ($request->file('nota')) {
                $dadosPedido['compra_antecipada'] = 'Sim';
            }

            $queryPedido = Pedido::create($dadosPedido);

            $idPedido = $queryPedido->id;

            // Caso existam parcelas inserir
            if ($request->input('parcelas')) {
                // 1º Passo -> Decodificar array
                $dadosParcelas = json_decode($request->input('parcelas'), true);
                // 2º Passo -> Inserir dados na tabela Parcela
                foreach ($dadosParcelas as $parcela) {
                    // Convertendo a string de data para um objeto Carbon
                    $dataVencimento = Carbon::parse($parcela['dataVencimento']);
                    // Formatando a data para o formato yy-mm-dd
                    $dataFormatada = $dataVencimento->format('Y-m-d');
                    Parcela::create([
                        'dt_vencimento' => $dataFormatada,
                        'valor' => $parcela['valor'],
                        'id_pedido' => $idPedido,
                        'status' => 'Pendente'
                    ]);
                }
            }

            // Caso o pedido foi realizado anteriormente
            if ($request->file('nota')) {
                // 1º Passo -> Salvar nota fiscal
                $directory = "/notas"; // Criando diretório

                $pdfNota = $request->file('nota')
                    ->store($directory, 'public'); // Salvando pdf da nota

                // 2º Passo -> Salvar informações
                $dadosNota = [
                    'nota' => $pdfNota,
                    'id_pedido' => $idPedido,
                    'dt_emissao' => $request->input('dt_emissao')
                ];

                NotasFiscais::create($dadosNota);
            }

            // Inserir boleto caso exista
            if ($request->file('boleto')) {
                $directory = "/boletos"; // Criando diretório

                $pdfBoleto = $request->file('boleto')
                    ->store($directory, 'public'); // Salvando pdf da boleto


                $dadosBoleto = [
                    'boleto' => $pdfBoleto,
                    'id_pedido' => $idPedido
                ];

                Boleto::create($dadosBoleto);
            }

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

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function cadastraPedidoSemFluxo($request)
    {
        DB::beginTransaction();

        try {
            // Validando envio de anexo
            if (!$request->file('anexo')) {
                // return ['resposta' => 'O envio do ANEXO é obrigatório!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
            }

            // 1º Passo -> Salvar arquivo e pegar hash gerado
            $directory = "/pedidos"; // Criando diretório

            $pdf = $request->file('anexo')->store($directory, 'public'); // Salvando pdf do pedido

            // 2º Passo -> Montar array a ser inserido
            $idLink = $request->input('id_link');
            $idStatus = ($idLink == 2) ? 1 : 2;

            if ($request->input('id_criador') == 4) {
                $dadosPedido = [
                    'descricao' => $request->input('descricao'),
                    'valor' => $request->input('valor'),
                    'urgente' => $request->input('urgente'),
                    'dt_vencimento' => $request->input('dt_vencimento'),
                    'dt_criacao_pedido' => $request->input('dt_criacao_pedido'),
                    'anexo' => $pdf,
                    'id_link' => $idLink,
                    'id_empresa' => $request->input('id_empresa'),
                    'id_status' => $idStatus,
                    'id_criador' => $request->input('id_criador'),
                    'id_local' => $request->input('id_local'),
                    'protheus' => intval($request->input('protheus')),
                    'tipo_pedido' => 'Sem Fluxo'
                ];
            } else {
                $dadosPedido = [
                    'descricao' => $request->input('descricao'),
                    'valor' => $request->input('valor'),
                    'urgente' => $request->input('urgente'),
                    'dt_vencimento' => $request->input('dt_vencimento'),
                    'dt_criacao_pedido' => $request->input('dt_criacao_pedido'),
                    'anexo' => $pdf,
                    'id_link' => $idLink,
                    'id_empresa' => $request->input('id_empresa'),
                    'id_status' => 6,
                    'id_criador' => $request->input('id_criador'),
                    'id_local' => $request->input('id_local'),
                    'protheus' => intval($request->input('protheus')),
                    'tipo_pedido' => 'Sem Fluxo'
                ];
            }

            if ($request->file('nota')) {
                $dadosPedido['compra_antecipada'] = 'Sim';
            }

            // 3º Passo -> Cadastrar pedido
            $queryPedido = Pedido::create($dadosPedido);

            $idPedido = $queryPedido->id;

            // Caso existam parcelas inserir
            if ($request->input('parcelas')) {
                // 1º Passo -> Decodificar array
                $dadosParcelas = json_decode($request->input('parcelas'), true);
                // 2º Passo -> Inserir dados na tabela Parcela
                foreach ($dadosParcelas as $parcela) {
                    // Convertendo a string de data para um objeto Carbon
                    $dataVencimento = Carbon::parse($parcela['dataVencimento']);
                    // Formatando a data para o formato yy-mm-dd
                    $dataFormatada = $dataVencimento->format('Y-m-d');
                    Parcela::create([
                        'dt_vencimento' => $dataFormatada,
                        'valor' => $parcela['valor'],
                        'id_pedido' => $idPedido,
                        'status' => 'Pendente'
                    ]);
                }
            }

            // Caso o pedido foi realizado anteriormente
            if ($request->file('nota')) {
                // 1º Passo -> Salvar nota fiscal
                $directory = "/notas"; // Criando diretório

                $pdfNota = $request->file('nota')
                    ->store($directory, 'public'); // Salvando pdf da nota

                // 2º Passo -> Salvar informações
                $dadosNota = [
                    'nota' => $pdfNota,
                    'id_pedido' => $idPedido,
                    'dt_emissao' => $request->input('dt_emissao')
                ];

                NotasFiscais::create($dadosNota);
            }

            // Inserir boleto caso exista
            if ($request->file('boleto')) {
                $directory = "/boletos"; // Criando diretório

                $pdfBoleto = $request->file('boleto')
                    ->store($directory, 'public'); // Salvando pdf da boleto


                $dadosBoleto = [
                    'boleto' => $pdfBoleto,
                    'id_pedido' => $idPedido
                ];

                Boleto::create($dadosBoleto);
            }


            // 5º Passo -> Cadastrar no historico_pedido
            $dadosHistorico = [
                'id_pedido' => $idPedido,
                'id_status' => $idStatus,
                'observacao' => 'O pedido foi enviado para o status ' . $idStatus
            ];


            $teste = HistoricoPedidos::create($dadosHistorico); // Inserindo log

            if ($queryPedido) {
                DB::commit();
                return ['resposta' => 'Pedido cadastrado com sucesso!', 'status' => Response::HTTP_CREATED];
            } else {
                return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
            }

            // 5º Passo -> Retornar resposta
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function listarEmFluxo($id)
    {
        // 1º Passo -> Buscar pedidos
        $query = FluxoPedidoResource::collection(Fluxo::where('assinado', 0)
            ->where('id_usuario', $id)
            ->whereHas('pedido', function ($query) {
                $query->where('id_status', 7);
            })
            ->get());

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

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function aprovaEmFluxoExterno($id, $idLink, $idUsuario)
    {
        DB::beginTransaction();

        try {
            // 1ª Passo -> Pegar fluxo do pedido referente a usuario que disparou a requisição
            $idFluxo = Fluxo::where('id_pedido', $id)
                ->where('id_usuario', $idUsuario)
                ->pluck('id')
                ->first();

            // 2ª Passo -> Aprovar pedido de acordo com id_fluxo enviado
            $query = Fluxo::where('id', $idFluxo)->update(['assinado' => 1]);

            // 3º Passo -> Alterar link de acordo com que
            Pedido::where('id', $id)->update(['id_link' => $idLink]);

            // 4º Passo -> Verificar se todo o fluxo referente a esse pedido foi aprovado
            $this->pedidosQuery->verificaFluxoAprovado($id);

            // 5º Passo -> Cadastra histórico
            $dados = [
                'id_pedido' => $id,
                'id_status' => 7,
                'observacao' => 'O pedido foi aprovado por um gerente/diretor!'
            ];

            $historico = HistoricoPedidos::create($dados); // Salvando

            // 6º Passo -> Retornar resposta
            if ($query) {
                DB::commit();
                return ['resposta' => 'Pedido aprovado com sucesso!', 'status' => Response::HTTP_OK];
            } else {
                return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
            }
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function reprovaEmFluxoExterno($id, $idUsuario, $mensagem)
    {
        try {

            // 1º Passo -> Alterar status do pedido para reprovado 10
            Pedido::where('id', $id)->update(['id_status' => 10]);

            // 2º Passo -> Iniciar chat da mensagem com o pq o pedido foi reprovado
            $dadosChat = [
                'id_pedido' => $id,
                'id_usuario' => $idUsuario,
                'mensagem' => $mensagem
            ];

            Chat::create($dadosChat);

            // 3º Passo -> Cadastrar na tabela historico
            $dadoHistorico = [
                'id_pedido' => $id,
                'id_status' => 10,
                'observacao' => 'O pedido foi reprovado por algum Gerente | Diretor'
            ];

            HistoricoPedidos::create($dadoHistorico);

            // 4º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Pedido reprovado com sucesso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function reprovarEmFluxo($id, $idUsuario, $mensagem)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Pegar id do pedido referente a esse fluxo
            $idPedido = Fluxo::where('id', $id)->pluck('id_pedido');

            // 2º Passo -> Alterar status do pedido para 10 (Fluxo Reprovado)
            Pedido::where('id', $idPedido[0])->update(['id_status' => 10]);

            // 3º Passo -> Cadastra histórico
            $dados = [
                'id_pedido' => $idPedido[0],
                'id_status' => 10,
                'observacao' => 'O pedido foi reprovado!'
            ];

            HistoricoPedidos::create($dados); // Salvando

            // 4º Passo -> Gerar chat com mensagem do pq o pedido foi reprovado
            $dadosChat = [
                'id_pedido' => $idPedido[0],
                'id_usuario' => $idUsuario,
                'mensagem' => $mensagem
            ];

            Chat::create($dadosChat);

            // 5º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Pedido reprovado com sucesso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function aprovarPedidoAcima($id)
    {

        DB::beginTransaction();

        try {
            // 1º Passo -> Aprovar pedido de acordo com id
            $query = Pedido::find($id);

            if ($query->compra_antecipada == 'Sim') {
                $query->id_status = 14;
            } else {
                $query->id_status = 4;
            }

            $query->save();

            // 2º Passo -> Gerar histórioco retornando id do histórico_gerado para possível delete
            $id_historico = HistoricoPedidos::create([
                'id_pedido' => $id,
                'id_status' => 4,
                'observacao' => 'Pedido aprovado'
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

    public function reprovarPedidoAcima($id, $mensagem, $idUsuario)
    {

        DB::beginTransaction();

        try {
            // 1º Passo -> Aprovar pedido de acordo com id
            $query = Pedido::find($id);
            $query->id_status = 3;
            $query->save();

            // 2º Passo -> Gerar histórioco retornando id do histórico_gerado para possível delete
            $id_historico = HistoricoPedidos::create([
                'id_pedido' => $id,
                'id_status' => 4,
                'observacao' => 'Pedido reprovado'
            ]);

            // 3º Passo -> Gerar chat com mensagem do presidente
            $dadosChat = [
                'id_pedido'  => $id,
                'id_usuario' => $idUsuario,
                'mensagem'   => $mensagem
            ];

            Chat::create($dadosChat);

            // 4º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Pedido reprovado com sucesso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function aprovarPedidoComRessalvaAcima($id, $idUsuario, $mensagem)
    {

        DB::beginTransaction();

        try {
            // 1º Passo -> Aprovar pedido de acordo com id
            $query = Pedido::find($id);
            $query->id_status = 5;
            $query->save();

            // 2º Passo -> Gerar histórioco retornando id do  histórico_gerado para possível delete
            $id_historico = HistoricoPedidos::create([
                'id_pedido' => $id,
                'id_status' => 4,
                'observacao' => 'Pedido aprovado com ressalva'
            ]);

            // 3º Passo -> Gerar chat com mensagem do presidente
            $dadosChat = [
                'id_pedido'  => $id,
                'id_usuario' => $idUsuario,
                'mensagem'   => $mensagem
            ];

            Chat::create($dadosChat);

            // 4º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Pedido aprovado ressalva com sucesso', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function listarQuantidades()
    {
        DB::beginTransaction();

        try {

            // 1º Passo -> Executar as 03 querys de listagem com filtros de preço para Emival

            $quantidades = [];

            $quantidades['qtd_abaixoQuinhentos'] = Pedido::whereIn('id_status', [1, 12])
                ->where('id_link', 2)
                ->where('valor', '<', 500.01) // Filtro para valores abaixo de 500
                ->count();

            $quantidades['qtd_abaixoMil'] = Pedido::whereIn('id_status', [1, 12])
                ->where('id_link', 2)
                ->where('valor', '>', 500.01)
                ->where('valor', '<', 1000.01) // Filtro para valores abaixo de 500
                ->count();


            $quantidades['qtd_acimaMil'] = Pedido::whereIn('id_status', [1, 12])
                ->where('id_link', 2)
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

    public function listarPedidosAprovados($id)
    {
        try {
            // 1º Passo -> Buscar todos pedido aprovador de acordo com id do usuário logado com status 4
            $pedidos = PedidoResource::collection(
                Pedido::where('id_criador', $id)
                    ->whereIn('id_status', [4, 5, 12, 13, 17, 18])
                    ->get()
            );

            // 2º Passo -> Retornar resposta
            return ['resposta' => 'Pedidos listado com sucesso!', 'pedidos' => $pedidos, 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function buscaInformacoesPedido($id)
    {
        try {
            // 1º Passo -> Buscar informações do pedido
            $pedido = Pedido::where('id', $id)->get();

            // 2º Passo -> Data de Aprovação do pedido por Emival
            $dtAprovacao = HistoricoPedidos::where('id_pedido', $id)
                ->where('observacao', 'O pedido foi aprovado pelo Dr. Emival!')
                ->pluck('created_at')
                ->first();

            // Adicionar a data de aprovação ao recurso
            $pedido = $pedido->map(function ($item) use ($dtAprovacao) {
                $item->dt_aprovacao = $dtAprovacao;
                return new PedidoAprovadoResource($item);
            });

            // 3º Passo -> Buscar informações na tabela fluxo para ver quando foi aprovado
            $query = FluxoAprovadoResource::collection(
                Fluxo::where('id_pedido', $id)->get()
            );

            // 4º Passo -> Retornar resposta
            return [
                'resposta' => 'Informações listadas com sucesso!',
                'pedido' => $pedido,
                'informacoes' => $query,
                'status' => Response::HTTP_OK
            ];
        } catch (\Exception $e) {
            return [
                'resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!',
                'pedido' => null,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];

            throw $e;
        }
    }

    public function buscaInformacoesPedidoAlterar($id)
    {
        try {
            // 1º Passo -> Buscar informações do pedido
            $pedido = PedidoInformacoesResource::collection(Pedido::where('id', $id)->get());

            return ['resposta' => 'Pedidos listados com sucesso!', 'pedido' => $pedido, 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            return [
                'resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!',
                'pedido' => null,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];

            throw $e;
        }
    }


    public function respondeReprovacaoComAnexo($request, $id)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Verifica se tem anexo e insere o mesmo
            if ($request->file('anexo')) {
                $directory = "/pedidos"; // Criando diretório

                $pdf = $request->file('anexo')->store($directory, 'public'); // Salvando pdf do pedido

                Pedido::where('id', $id)
                    ->update(['anexo' => $pdf]);
            }

            // 2º Passo -> Pegar para quem deve ser enviar de acordo com o campo id_link
            $link = Pedido::where('id', $id)
                ->pluck('id_link')
                ->first();

            if ($link == 2) {
                $id_status = 1;
            } else {
                $id_status = 2;
            }

            // 3º Passo -> Inserir mensagem na tabela chat
            $dadosChat = [
                'id_pedido' => $id,
                'id_usuario' => $request->input('id_criador'),
                'mensagem' => $request->input('mensagem')
            ];

            Chat::create($dadosChat);

            // 4º Passo -> Atualizar o status do pedido
            Pedido::where('id', $id)
                ->update(['id_status' => $id_status]);

            // 5º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Mensagem enviada com sucesso!', 'status' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function listarRessalva($id)
    {
        // 1º Passo -> Buscar pedidos com status 5
        $query = PedidoResource::collection(
            Pedido::whereIn('id_status', [5, 13]) // Substitua [1, 2, 3] pelos valores desejados
                ->where('id_local', $id)
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos aprovados com ressalva!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function respondeRessalvaPedido($request, $id)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Salvar Mensagem
            $dadosMEnsagem = [
                'id_pedido' => $id,
                'id_usuario' => $request->input('id_usuario'),
                'mensagem' => $request->input('mensagem')
            ];

            Chat::create($dadosMEnsagem);

            // 3º Passo -> Alterar status do pedido
            Pedido::where('id', $id)->update(['id_status' => 12]);

            // 4º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Pedido respondido com sucesso!', 'status' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
            throw $e;
        }
    }

    public function listarReprovadosFluxo($id)
    {
        // 1º Passo -> Buscar todos pedidos com status 10
        $query = PedidoFluxoResource::collection(
            Pedido::orderBy('created_at', 'desc')
                ->where('id_criador', $id)
                ->where('id_status', 10)
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarReprovadosSoleni($id)
    {
        // 1º Passo -> Buscar todos pedidos com status 10
        $query = PedidoFluxoResource::collection(
            Pedido::orderBy('created_at', 'desc')
                ->where('id_criador', $id)
                ->where('id_status', 11)
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function respondeReprovacaoEmFluxo($request, $id, $idUsuario, $mensagem)
    {
        DB::beginTransaction();
        try {
            // 1º Passo -> Verifica se tem anexo e insere o mesmo
            if ($request->hasFile('anexo')) {
                $directory = "pedidos"; // Diretório onde o arquivo será armazenado

                // Salvando o pdf do pedido no diretório público
                $pdf = $request->file('anexo')->store($directory, 'public');

                // Atualizando a coluna 'anexo' do pedido com o caminho do arquivo armazenado
                Pedido::where('id', $id)->update(['anexo' => $pdf]);
            }

            // 2º Passo -> Alterar status do pedido
            Pedido::where('id', $id)->update(['id_status' => 7]);

            // 3º Passo -> Inserir mensagem na tabela chat
            $dadosChat = [
                'id_pedido' => $id,
                'id_usuario' => $idUsuario,
                'mensagem' => $mensagem
            ];

            Chat::create($dadosChat);

            // 4º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Mensagem enviada com sucesso!', 'status' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['resposta' => $e, 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }


    public function respondeReprovacaoSoleni($request, $id)
    {
        DB::beginTransaction();
        try {
            // 1º Passo -> Verifica se tem anexo e insere o mesmo
            if ($request->file('anexo')) {
                $directory = "/pedidos"; // Criando diretório

                $pdf = $request->file('anexo')->store($directory, 'public'); // Salvando pdf do pedido

                Pedido::where('id', $id)
                    ->update(['anexo' => $pdf]);
            }

            // 2º Passo -> Alterar status do pedido
            $teste = Pedido::where('id', $id)
                ->update(['id_status' => 6]);

            // 3º Passo -> Inserir mensagem na tabela chat
            $dadosChat = [
                'id_pedido' => $id,
                'id_usuario' => $request->input('id_usuario'),
                'mensagem' => $request->input('mensagem')
            ];

            Chat::create($dadosChat);

            // 5º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Mensagem enviada com sucesso!', 'status' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function respondeReprovacaoFinanceiro($request, $id)
    {
        DB::beginTransaction();
        try {
            // 1º Passo -> Verifica se tem anexo e insere o mesmo
            if ($request->file('anexo')) {
                $directory = "/notas"; // Criando diretório

                $pdf = $request->file('anexo')->store($directory, 'public'); // Salvando pdf do pedido

                Pedido::where('id', $id)
                    ->update(['anexo' => $pdf]);

                NotasFiscais::where('id_pedido', $id)
                    ->update(['nota' => $pdf]);
            }

            // 2º Passo -> Alterar status do pedido
            Pedido::where('id', $id)
                ->update(['id_status' => 15]);

            // 3º Passo -> Inserir mensagem na tabela chat
            $dadosChat = [
                'id_pedido' => $id,
                'id_usuario' => $request->input('id_usuario'),
                'mensagem' => $request->input('mensagem')
            ];

            Chat::create($dadosChat);

            // 5º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Mensagem enviada com sucesso!', 'status' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function aprovaEmFluxoDiretor($id, $idLink, $urgente)
    {
        DB::beginTransaction();

        try {

            // 1ª Passo -> Aprovar pedido de acordo com id_fluxo enviado
            $query = Fluxo::where('id', $id)->update(['assinado' => 1]);

            // 2º Passo -> Pegar id do pedido referente a esse fluxo
            $idPedido = Fluxo::where('id', $id)->pluck('id_pedido');

            // 3º Passo -> Altererar para quem vai ser enviado o pedido EMIVAL OU MONICA
            Pedido::where('id', $idPedido[0])->update(['id_link' => $idLink, 'urgente' => $urgente]);

            // 4º Passo -> Verificar se todo o fluxo referente a esse pedido foi aprovado
            $this->pedidosQuery->verificaFluxoAprovado($idPedido);

            // 5º Passo -> Cadastra histórico
            $dados = [
                'id_pedido' => $idPedido[0],
                'id_status' => 7,
                'observacao' => 'O pedido foi aprovado por um gerente/diretor!'
            ];

            $historico = HistoricoPedidos::create($dados); // Salvando

            // 6º Passo -> Retornar resposta
            if ($query) {
                DB::commit();
                return ['resposta' => 'Pedido aprovado com sucesso!', 'status' => Response::HTTP_OK];
            } else {
                return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
            }
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function atualizaDadosPedido($request, $id)
    {
        // 1º Passo -> montar array de acordo com dados enviados para ser atualizado
        $dados = [];

        if ($request->has('descricao')) {
            $dados['descricao'] = $request->input('descricao');
        }

        if ($request->has('valor')) {
            $dados['valor'] = $request->input('valor');
        }

        if ($request->has('urgente')) {
            $dados['urgente'] = intval($request->input('urgente'));
        }

        if ($request->has('dt_vencimento')) {
            $dados['dt_vencimento'] = $request->input('dt_vencimento');
        }

        if ($request->has('id_empresa')) {
            $dados['id_empresa'] = intval($request->input('id_empresa'));
        }

        if ($request->has('protheus')) {
            $dados['protheus'] = intval($request->input('protheus'));
        }

        // 2º Passo -> Se existir pdf novo cadastrar pdf e adicionar o caminho no array para atualizar
        if ($request->file('anexo')) {
            // 1º Passo -> Salvar arquivo e pegar hash gerado
            $directory = "/pedidos"; // Criando diretório
            $pdf = $request->file('anexo')->store($directory, 'public'); //
            $dados['anexo'] = $pdf;
        }

        if (!empty($dados)) {
            $query = Pedido::where('id', $id)->update($dados);
            return ['resposta' => 'Pedido atualizado com sucesso!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'É obrigatório a alteração de pelo menos 1 campo', 'status' => Response::HTTP_ACCEPTED];
        }
    }

    public function listarPedidosEscriturar()
    {
        // 1º Passo -> Buscar todos pedidos com status 14
        $query = NotasResource::collection(
            NotasFiscais::whereHas('pedidos', function ($query) {
                $query->where('id_status', 14);
            })
                ->where('status', 'Pendente')
                ->orderBy('created_at', 'desc')
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listado com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarPedidosReprovadosFiscal($id)
    {
        // 1º Passo -> Buscar todos pedidos com status 14
        $query = NotasResource::collection(
            NotasFiscais::whereHas('pedidos', function ($query) use ($id) {
                $query->where('id_status', 16)
                    ->where('id_criador', $id);
            })
                ->where('status', 'Pendente')
                ->orderBy('created_at', 'desc')
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listado com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function respondeReprovacaoFiscal($request, $id)
    {
        DB::beginTransaction();
        try {
            // 1º Passo -> Verifica se tem anexo e insere o mesmo
            if ($request->file('nota')) {
                $directory = "/notas"; // Criando diretório

                $pdf = $request->file('nota')->store($directory, 'public'); // Salvando pdf do pedido

                NotasFiscais::where('id', $id)
                    ->update(['nota' => $pdf]);
            }

            // 2º Passo -> Alterar status do pedido para 14
            $idPedido = NotasFiscais::where('id', $id)
                ->pluck('id_pedido')
                ->first();

            // 3º Passo -> Inserir mensagem na tabela chat
            $dadosChat = [
                'id_pedido' => $idPedido,
                'id_usuario' => $request->input('id_usuario'),
                'mensagem' => $request->input('mensagem')
            ];

            Chat::create($dadosChat);

            // 4º Passo -> Alterar Status do Pedido
            Pedido::where('id', $idPedido)->update(['id_status' => 14]);

            // 5º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Mensagem enviada com sucesso!', 'status' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['resposta' => $e, 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function listarPedidosFinanceiro()
    {
        // 1º Passo -> Pegar todos pedidos com status 15
        $pedidos = PedidosEnviadosFinanceiroResource::collection(
            Pedido::where('id_status', 15)
                ->where('parcelas_validadas', 'Não')
                ->orderBy('dt_vencimento', 'desc')
                ->get()
        );

        // 2º Passo -> Retornar pedidos
        return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $pedidos, 'status' => Response::HTTP_OK];
    }

    public function pagarPedido($request, $id)
    {
        try {
            // 1º Passo -> Inserir diretório onde foi salvo o comprovante na tabela pedidos
            Pedido::where('id', $id)->update(['comprovante' => $request->input('comprovante')]);

            // 2º Passo -> Verificar se existe nota associada a esse pedido na tabela notas fiscais se existir enviar para Patricia e Vinicius
            $verificaNota = NotasFiscais::where('id_pedido', $id)->count();

            if ($verificaNota) {
                Pedido::where('id', $id)->update(['id_status' => 17]); // Enviando para consolida
            } else {
                Pedido::where('id', $id)->update(['id_status' => 18]); // Enviando para comprador anexa nota
            }

            // 3º Passo -> Alterar na tabela Boletos que foi pago
            Boleto::where('id_pedido', $id)->update(['status' => 'Pago']);

            // 4º Passo -> Retornar resposta
            DB::commit();

            return ['resposta' => 'Pedido despachado com sucesso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['resposta' => $e, 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function reprovarPedidoEnviadoFinanceiroComprador($request, $id)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Alterar Status do Pedido para 19
            Pedido::where('id', $id)->update(['id_status' => 19]);

            // 2º Passo -> Gerar chat com motivo da reprovacao
            Chat::create([
                'id_usuario' => $request->input('id_usuario'),
                'id_pedido'  => intval($id),
                'mensagem'   => $request->input('mensagem')
            ]);

            // 3º Passo -> Gerar Histórico da reprovação
            HistoricoPedidos::create([
                'id_pedido' => $id,
                'id_status'  => 19,
                'observacao' => 'Pedido reprovado pelo financeiro!'
            ]);

            // 4º Passo -> Retornar resposta
            DB::commit();

            return ['resposta' => 'Pedido reprovado e enviado para comprador com sucesso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function reprovarPedidoEnviadoFinanceiroFiscal($request, $id)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Alterar Status do Pedido para 20
            Pedido::where('id', $id)->update(['id_status' => 20]);

            // 2º Passo -> Gerar chat com motivo da reprovacao
            Chat::create([
                'id_usuario' => $request->input('id_usuario'),
                'id_pedido'  => $id,
                'mensagem'   => $request->input('mensagem')
            ]);

            // 3º Passo -> Gerar Histórico da reprovação
            HistoricoPedidos::create([
                'id_pedido' => $id,
                'id_status'  => 20,
                'observacao' => 'Pedido reprovado pelo financeiro!'
            ]);

            // 4º Passo -> Retornar resposta
            DB::commit();

            return ['resposta' => 'Pedido reprovado e enviado para fiscal com sucesso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollBack();

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function listarReprovadosFinanceiro()
    {
        // 1º Passo -> Buscar todos pedidos com status 20
        $query = PedidoFluxoResource::collection(
            Pedido::orderBy('created_at', 'desc')
                ->where('id_status', 20)
                ->get()
        );
        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador',  'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function alterarUrgente($id)
    {
        // 1º Passo -> Alterar pedido para urgente
        $query = Pedido::where('id', $id)->update(['urgente' => 1]);

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedido foi definido como urgente com sucesso!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador',  'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function alterarNormal($id)
    {
        // 1º Passo -> Alterar pedido para urgente
        $query = Pedido::where('id', $id)->update(['urgente' => 0]);

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedido foi definido como normal com sucesso!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador',  'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function relatorioEmival()
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Buscar todos pedidos no link limitando com 500
            $query = PedidoRelatorioEmivalResource::collection(
                Pedido::orderBy('compra_antecipada', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->where('id_link', 2)
                    ->where('id_status', '!=', 8)
                    ->take(500)
                    ->get()
            );

            // 2º Passo -> Ver a quantidade de pedidos
            $totalPedidos = Pedido::orderBy('created_at', 'desc')
                ->where('id_link', 2)
                ->where('id_status', '!=', 3)
                ->take(500)
                ->count();

            // 3º Passo -> Ver valor
            $totalValor =  Pedido::orderBy('created_at', 'desc')
                ->where('id_link', 2)
                ->where('id_status', '!=', 3)
                ->take(500)
                ->sum('valor');

            // 4º Passo -> Retornar resposta
            return [
                'resposta' => 'Pedidos listado com sucesso!',
                'totalPedidos' => $totalPedidos,
                'totalValor' => $totalValor,
                'pedidos' => $query,
                'status' => Response::HTTP_OK
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'resposta' => $e,
                'pedidos' => null,
                'totalPedidos' => null,
                'totalValor' => null,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
            throw $e;
        }
    }

    public function listarControleFinanceiro()
    {
        // 1º Passo -> Buscar todos pedidos e suas respectivas parcelas
        $query = PedidosComParcelasResource::collection(Pedido::where('id_status', 21)->get());

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarControleFinanceiroFiltro($idEmpresa)
    {
        // 1º Passo -> Buscar todos pedidos e suas respectivas parcelas de acordo com empresa passada
        $query = PedidosComParcelasResource::collection(
            Pedido::where('id_status', 21)
                ->where('id_empresa', $idEmpresa)
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Pedidos listados com sucesso!', 'pedidos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'pedidos' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function auditoriaFinanceiro()
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Buscar todos pedidos no link limitando com 500
            $query = PedidosAuditoriaFinanceiro::collection(
                Pedido::orderBy('compra_antecipada', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->where('id_link', 2)
                    ->where('id_status', '!=', 3)
                    ->take(500)
                    ->get()
            );

            // 2º Passo -> Ver a quantidade de pedidos
            $totalPedidos = Pedido::orderBy('created_at', 'desc')
                ->where('id_link', 2)
                ->where('id_status', '!=', 3)
                ->take(500)
                ->count();

            // 3º Passo -> Ver valor
            $totalValor =  Pedido::orderBy('created_at', 'desc')
                ->where('id_link', 2)
                ->where('id_status', '!=', 3)
                ->take(500)
                ->sum('valor');

            // 4º Passo -> Retornar resposta
            return [
                'resposta' => 'Pedidos listado com sucesso!',
                'totalPedidos' => $totalPedidos,
                'totalValor' => $totalValor,
                'pedidos' => $query,
                'status' => Response::HTTP_OK
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'resposta' => $e,
                'pedidos' => null,
                'totalPedidos' => null,
                'totalValor' => null,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
            throw $e;
        }
    }
}
