<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Parcela;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Pedido;
use App\Http\Resources\ParcelaResource;

class ParcelaService
{
    public function cadastrarParcela($request, $id)
    {
        DB::beginTransaction();

        try {

            // 1º Passo -> Decodificar array
            $dadosParcelas = $request->input('parcelas');

            // 2º Passo -> Inserir dados na tabela Parcela
            foreach ($dadosParcelas as $parcela) {
                // Convertendo a string de data para um objeto Carbon
                $dataVencimento = Carbon::parse($parcela['dataVencimento']);
                // Formatando a data para o formato yy-mm-dd
                $dataFormatada = $dataVencimento->format('Y-m-d');

                Parcela::create([
                    'dt_vencimento' => $dataFormatada,
                    'valor' => $parcela['valor'],
                    'id_pedido' => intval($id),
                    'status' => 'Pendente'
                ]);
            }

            // 3º Passo -> Alterar status do pedido para 21
            Pedido::where('id', $id)->update(['id_status' => 21]);

            // 4º Passo -> Retornar respostas
            DB::commit();
            return ['resposta' => 'Parcelas cadastradas com sucesso!', 'status' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function buscaParcelasHoje()
    {
        DB::beginTransaction();

        try {

            // 1º Passo -> Pegar data atual
            $currentDate = Carbon::now('America/Sao_Paulo');
            $dataFormatada = $currentDate->format('Y/m/d');

            // 2º Passo -> Buscar todas parcelas com a data atual
            $parcelas = ParcelaResource::collection(Parcela::where('dt_vencimento', $dataFormatada)->get());

            // 3º Passo -> Fazer a soma de todas parcelas
            $total = Parcela::where('dt_vencimento', $dataFormatada)->sum('valor');

            // 4º Passo -> Total de Pagamentos no dia
            $totalParcelas = Parcela::where('dt_vencimento', $dataFormatada)->count('id');

            // 5º Passo -> Retornar resposta
            return ['resposta' => 'Parcelas listadas com sucesso!', 'parcelas' => $parcelas, 'total' => $total, 'totalParcelas' => $totalParcelas, 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'parcelas' => null, 'total' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function buscaParcelas($request)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Validar e formatar as datas de entrada
            $dataInicio = Carbon::parse($request->input('dataInicio'))->format('Y-m-d');
            $dataFim = Carbon::parse($request->input('dataFim'))->format('Y-m-d');

            // 2º Passo -> Buscar todas parcelas entre as datas
            $parcelas = ParcelaResource::collection(
                Parcela::whereBetween('dt_vencimento', [$dataInicio, $dataFim])->get()
            );

            // 3º Passo -> Fazer a soma de todas parcelas entre as datas
            $total = Parcela::whereBetween('dt_vencimento', [$dataInicio, $dataFim])->sum('valor');

            // 4º Passo -> Total de Pagamentos no dia
            $totalParcelas = Parcela::whereBetween('dt_vencimento', [$dataInicio, $dataFim])->count('id');

            // 5º Passo -> Retornar resposta
            return ['resposta' => 'Parcelas listadas com sucesso!', 'parcelas' => $parcelas, 'total' => $total, 'totalParcelas' => $totalParcelas, 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'parcelas' => null, 'total' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }
}
