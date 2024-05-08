<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\User;
use App\Http\Resources\UserResource;

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

}
