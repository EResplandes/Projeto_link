<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Models\Pedido;

use function PHPUnit\Framework\isEmpty;

class ExternoService
{

    public function listarGerentes()
    {
        // 1º Passo -> Buscar todos funcionários
        $query = UserResource::collection(User::where('id_funcao', 2)->get());

        // 2º Passo -> Retornar dados
        if ($query) {
            return ['resposta' => 'Funcionários listados com sucesso!', 'funcionarios' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function listarDiretores()
    {
        // 1º Passo -> Buscar todos funcionários
        $query = UserResource::collection(User::where('id_funcao', 3)->get());

        // 2º Passo -> Retornar dados
        if ($query) {
            return ['resposta' => 'Funcionários listados com sucesso!', 'funcionarios' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function listarPresidentes()
    {
        // 1º Passo -> Buscar todos funcionários
        $query = UserResource::collection(User::where('id_funcao', 5)->get());

        // 2º Passo -> Retornar dados
        if ($query) {
            return ['resposta' => 'Funcionários listados com sucesso!', 'funcionarios' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function cadastrarPedido($request)
    {
        // 1º Passo -> Buscar id do funcionario logado atráves do e-mail passado
        $idCriador = User::where('email', $request->input('email'))->pluck('id')->first();

        // 2º Passo -> Cadastrar pedido
        $dados = [
            'descricao' => $request->input('descricao'),
            'valor' => $request->input('valor'),
            'protheus' => 999,
            'urgente' => $request->input('urgente'),
            'dt_vencimento' => $request->input('dt_vencimento'),
            'anexo' => $request->input('anexo'),
            'id_link' => $request->input('id_link'),
            'id_empresa' => $request->input('id_empresa'),
            'id_criador' => $idCriador,
            'id_local' => $request->input('id_local')
        ];

        // Verifica se pedido é com fluxo e sem fluxo para status e campo com fluxo
        if (isEmpty($request->input('fluxo'))) {
            $dados['tipo_pedido'] = 'Sem Fluxo';
        }

        if (isEmpty($request->input('fluxo'))) {
            $dados['id_status'] = 6;
        } else {
            $dados['id_status'] = 7;
        }

        Pedido::create($dados);

        // 3º Passo -> Verificar se tem fluxo paara cadastro de fluxo


        // 4º Passo -> Cadastro no histórico informando que pedido é externo
        // 5º Passo -> Retornar resposta com o id do pedido
    }
}
