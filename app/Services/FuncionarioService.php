<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\Models\Funcao;
use App\Models\Grupo;

class FuncionarioService
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

    public function listarFuncionarios()
    {
        // 1º Passo -> Buscar todos usuários com suas respectivas permissões e funções
        $query = UsersResource::collection(User::all());

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Usuários listados com sucesso!', "usuarios" => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function cadastrar($request)
    {
        // 1º Passo -> Montar array a ser inserido
        $dados = [
            'name' => $request->input('nome'),
            'email' => $request->input('email'),
            'password' => bcrypt('!Rialma2023'),
            'id_funcao' => $request->input('id_funcao'),
            'id_grupo' => $request->input('id_grupo'),
            'id_local' => $request->input('id_local'),
            'primeiro_acesso' => 1,
            'status' => "Ativo"
        ];

        // 2ª Passo -> Cadastrar funcionário
        $query = User::create($dados);

        // 3ª Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Usuário criado com sucesso!', 'status' => Response::HTTP_CREATED];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function listarGrupos()
    {
        // 1ª Passo -> Buscar grupos
        $query = Grupo::all();

        // 2ª Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Grupos listados com sucesso!', 'grupos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'grupos' => null, 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function listarFuncoes()
    {
        // 1ª Passo -> Buscar Funções
        $query = Funcao::all();

        // 2ª Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Funções listadas com sucesso!', 'funcoes' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'funcoes' => null, 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function listarResponsaveis()
    {
        // 1º Passo -> Buscar todos usuários com suas respectivas permissões e funções
        $query = UsersResource::collection(
            User::whereIn('id_funcao', [2, 3])
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Funcionários listados com sucesso!', "funcionarios" => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function desativaFuncionario($id)
    {

        // 1ª Passo -> Desativar Funcionário
        $query = User::where('id', $id)->update(['status' => 'Desativado']);

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Funcionário desativado com sucesso!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function ativaFuncionario($id)
    {
        // 1ª Passo -> Desativar Funcionário
        $query = User::where('id', $id)->update(['status' => 'Ativo']);

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Funcionário ativado com sucesso!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Ocorreu algum problema, entre em contato com o Administrador!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }
}
