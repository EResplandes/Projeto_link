<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Caixa;
use App\Models\ControleCaixa;
use Illuminate\Support\Facades\DB;

class CaixaService
{

    public function listar()
    {
        // 1º Passo -> Buscar todos caixas
        $query = Caixa::all(); // 

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Caixas listados com sucesso', 'caixas' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'caixas' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function cadastrar($request)
    {
        // 1º Passo -> Montar array a ser inserido
        $dados = [
            'funcionario' => $request->input('funcionario'),
            'funcao_funcionario' => $request->input('funcao_funcionario'),
            'banco' => $request->input('banco'),
            'agencia' => $request->input('agencia'),
            'conta' => $request->input('conta'),
            'cpf' => $request->input('cpf')
        ];

        // Verificando se existe anexo para inserir no array
        if ($request->input('anexo')) {
            $dados['anexo'] = $request->input('anexo');
        }

        // 2º Passo -> Inserir na tabela caixas
        $query = Caixa::create($dados);

        // 3º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Caixa cadastrado com sucesso!', 'status' => Response::HTTP_CREATED];
        } else {
            return ['resposta' => 'Ocorreu um erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function excluir($id)
    {
        // 1º Passo -> Deletar caixa
        $query = Caixa::where('id', $id)->delete();

        // 2º Passo-> Retornar resposta
        if ($query) {
            return ['resposta' => 'Caixa excluído com sucesso!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu um erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function cadastrarControleCaixa($request)
    {
        // 1º Passo -> Montar array a ser inserido
        $dados = [
            'id_caixa' => $request->input('id_caixa'),
            'dt_lancamento' => $request->input('dt_lancamento'),
            'discriminacao' => $request->input('discriminacao'),
            'tipo_caixa' => $request->input('tipo_caixa')
        ];

        if ($request->input('debito')) {
            $dados['debito'] = $request->input('debito');
        }

        if ($request->input('credito')) {
            $dados['credito'] = $request->input('credito');
        }

        if ($request->input('observacao')) {
            $dados['observacao'] = $request->input('observacao');
        }

        // 2º Passo -> Inserir na tabela controle caixas
        $query = ControleCaixa::create($dados);

        // 3º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Controle de caixa inserido com sucesso!', 'status' => Response::HTTP_CREATED];
        } else {
            return ['resposta' => 'Ocorreu um erro, entre em contato com o Administrador!', 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function listarControleCaixa($idCaixa, $tipoCaixa)
    {
        // 1º Passo -> Buscar controles de caixas
        $query = ControleCaixa::where('id_caixa', $idCaixa)->where('tipo_caixa', $tipoCaixa)->get();

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Controles de caixa listados com sucesso!', 'controles' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu um erro, entre em contato com o Administrador!', 'controles' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }

    public function filtrarCaixaPorFuncionario($request)
    {
        // 1º Passo -> Buscar controle de caixa de acordo com nome do funcionário
        $query = Caixa::where('funcionario', 'like', '%' . $request->input('funcionario') . '%')->get();

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Controles de caixa listados com sucesso!', 'caixas' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu um erro, entre em contato com o Administrador!', 'caixas' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];
        }
    }
}
