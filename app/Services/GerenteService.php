<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PedidoResource;
use App\Models\Chat;
use App\Models\Fluxo;
use App\Models\HistoricoPedidos;
use App\Queries\PedidosQuery;

class GerenteService
{

    protected $pedidosQuery;

    public function __construct(PedidosQuery $pedidosQuery)
    {
        $this->pedidosQuery = $pedidosQuery;
    }


    public function listarReprovadosRessalvaEmivalGerente($id)
    {
        DB::beginTransaction();

        try {
            $pedidosComFluxo = PedidoResource::collection(Pedido::whereIn('id_status', [3, 5, 24])
                ->whereHas('fluxo', function ($query) use ($id) {
                    $query->where('id_usuario', $id);
                })
                ->get());

            $pedidosSemFluxo = PedidoResource::collection(
                Pedido::whereIn('id_status', [3, 5, 24])
                    ->where('tipo_pedido', 'Sem Fluxo')
                    ->get()
            );

            return [
                'resposta' => 'Pedidos listados com sucesso!',
                'pedidos_com_fluxo' => $pedidosComFluxo,
                'pedidos_sem_fluxo' => $pedidosSemFluxo,
                'status' => Response::HTTP_OK
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'resposta' => 'Erro ao listar pedidos: ' . $e->getMessage(),
                'pedidos_com_fluxo' => null,
                'pedidos_sem_fluxo' => null,
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }


    public function respoondeMensagemEmival($idPedido, $request)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Salvar Mensagem
            $dadosMEnsagem = [
                'id_pedido' => $idPedido,
                'id_usuario' => $request->input('id_usuario'),
                'mensagem' => $request->input('mensagem')
            ];

            Chat::create($dadosMEnsagem);

            // 3º Passo -> Alterar status do pedido
            Pedido::where('id', $idPedido)->update(['id_status' => 12]);

            // 4º Passo -> Retornar resposta
            DB::commit();
            return ['resposta' => 'Pedido respondido com sucesso!', 'status' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
            throw $e;
        }
    }


    public function encontrarPdf($id)
    {
        $diretorioPdf = Pedido::where('id', $id)->pluck('anexo')->first();

        $path = storage_path('app/public/' . $diretorioPdf);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }


    public function aprovarPedidoGerente($request)
    {
        DB::beginTransaction();

        try {
            // 1ª Passo -> Aprovar pedido de acordo com id_fluxo enviado
            $query = Fluxo::where('id', $request->idFluxo)->update(['assinado' => 1]);

            // 2º Passo -> Pegar id do pedido referente a esse fluxo
            $idPedido = Fluxo::where('id', $request->idFluxo)->pluck('id_pedido'); 

            // Verificar se o idPedido foi encontrado
            if (!$idPedido) {
                return ['resposta' => 'Pedido não encontrado!', 'status' => Response::HTTP_NOT_FOUND];
            }

            // 3º Passo -> Alterar para quem vai ser enviado o pedido EMIVAL OU MONICA OU GIOVANA
            Pedido::where('id', $idPedido)->update(['id_link' => $request->idLink, 'urgente' => $request->urgente]);

            // 4º Passo -> Verificar se todo o fluxo referente a esse pedido foi aprovado
            $this->pedidosQuery->verificaFluxoAprovado($idPedido);

            // 5º Passo -> Cadastra histórico
            $dados = [
                'id_pedido' => $idPedido[0],
                'id_status' => 7,
                'observacao' => 'O pedido foi aprovado por um gerente/diretor!'
            ];

            $historico = HistoricoPedidos::create($dados); // Salvando

            // 6º Passo -> Cadastra pdf assinado
            $ano = date('Y'); // Ano atual
            $mes = date('m'); // Mês atual
            $directory = "/pedidos/{$ano}/{$mes}"; // Criando diretório ano/mês

            // Salvar novo PDF do pedido
            $pdf = $request->file('anexo')->store($directory, 'public');
            
            // Atualizar o registro do pedido com o novo anexo
            Pedido::where('id', $idPedido)->update(['anexo' => $pdf]); // Atualiza o campo 'anexo' com o novo arquivo

            // 7º Passo -> Retornar resposta
            DB::commit();
            return [
                'resposta' => [
                    'mensagem' => 'Pedido aprovado com sucesso!',
                    'id_pedido' => $idPedido,
                    'anexo' => $pdf // Informações adicionais sobre o que foi salvo
                ],
                'status' => Response::HTTP_OK
            ];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            // Retornar uma resposta amigável em vez da exceção
            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }
}
