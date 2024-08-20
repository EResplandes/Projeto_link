<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Parcela;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Pedido;
use App\Http\Resources\ParcelaResource;
use App\Models\Chat;

class ParcelaService
{
    public function cadastrarParcela($request, $id)
    {
        DB::beginTransaction();

        try {

            // 1º Passo -> Decodificar arrayxR
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

            // 3º Passo -> Alterar status do pedido para 15
            Pedido::where('id', $id)->update(['id_status' => 15]);

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
            $parcelas = ParcelaResource::collection(
                Parcela::where('dt_vencimento', $dataFormatada)
                    ->where('validado', 'Sim')
                    ->where('status', 'Pendente')
                    ->get()
            );

            // 3º Passo -> Fazer a soma de todas parcelas
            $total = Parcela::where('dt_vencimento', $dataFormatada)
                ->where('validado', 'Sim')
                ->where('status', 'Pendente')
                ->sum('valor');

            // 4º Passo -> Total de Pagamentos no dia
            $totalParcelas = Parcela::where('dt_vencimento', $dataFormatada)
                ->where('validado', 'Sim')
                ->where('status', 'Pendente')
                ->count('id');

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
                Parcela::whereBetween('dt_vencimento', [$dataInicio, $dataFim])
                    ->get()
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

    public function buscaParcelasFiltradas($dtInicio, $dtFim)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Validar e formatar as datas de entrada
            $dataInicio = Carbon::parse($dtInicio)->format('Y-m-d');
            $dataFim = Carbon::parse($dtFim)->format('Y-m-d');

            // 2º Passo -> Buscar todas parcelas entre as datas
            $parcelas = ParcelaResource::collection(
                Parcela::whereBetween('dt_vencimento', [$dataInicio, $dataFim])
                    ->where('validado', 'Sim')
                    ->where('status', 'Pendente')
                    ->get()
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

    public function darBaixa($id, $idBanco)
    {
        // 1º Passo -> Definir como pago
        $query = Parcela::where('id', $id)->update([
            'status' => 'Pago',
            'id_banco' => $idBanco,
            'dt_pagamento' => Carbon::now('America/Sao_Paulo')
        ]);

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Parcela paga com sucesso!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function validarParcelas($id)
    {

        DB::beginTransaction();

        try {
            // 1º Passo -> Alterar campos para sim de todas parcelas com o id do pedido referente
            $query = Parcela::where('id_pedido', $id)->update([
                'validado' => 'Sim',
                'dt_validacao' => Carbon::now('America/Sao_Paulo')
            ]);

            // 2º Passo -> Alterar na tabela pedidos informando que as parcelas já foram validas
            Pedido::where('id', $id)->update([
                'parcelas_validadas' => 'Sim',
            ]);

            // 2º Passo -> Retornar resposta
            if ($query) {
                DB::commit();
                return ['resposta' => 'Validação de parcelas realizada com sucesso!', 'status' => Response::HTTP_OK];
            } else {
                return ['resposta' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
            }
        } catch (\Exception $e) {
            DB::rollBack(); // Refaz alterações no banco de dados
            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function reprovarParcelas($request)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Apagar todas parcelas e enviar pedido para o comprador inserir novamente as parcelas do pedido
            Parcela::where('id_pedido', $request->input('id_pedido'))->delete();

            // 2º Passo -> Alterar status do Pedido
            Pedido::where('id', $request->input('id_pedido'))->update(['id_status' => 19]);

            // 3º Passo -> Gerar chat com motivo da reprovação
            $dadosChat = [
                'id_pedido' => $request->input('id_pedido'),
                'id_usuario' => $request->input('id_usuario'),
                'mensagem' => $request->input('mensagem')
            ];

            Chat::create($dadosChat);

            // 4º Passo -> Retornar resposta
            DB::commit();

            return ['resposta' => 'Pedido reprovado com sucesso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function alterarDadosParcela($request)
    {

        $dataFormatada = Carbon::parse($request->input('dt_vencimento'));
        $dataFinal = $dataFormatada->format('d/m/Y');

        // 1º Passo -> Alterar dados de parcela especifica de acordo com parcela
        $query = Parcela::where('id', $request->input('id_parcela'))
            ->update([
                'valor' => $request->input('valor'),
                'dt_vencimento' => $dataFormatada
            ]);

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Informações atualizadas com sucesso!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu um erro, entre em contato com o Administrador', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function deletarParcela($id)
    {
        // 1º Passo -> Apagar parcela de acordo com id passado
        $query = Parcela::where('id', $id)
            ->delete();

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Parcela deletada com sucesso!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu um erro, entre em contato com o Administrador', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }
}
