<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Queries\UsersQuery;

class AutenticacaoService
{

    protected $usersQuery;

    public function __construct(UsersQuery $usersQuery)
    {
        $this->usersQuery = $usersQuery;
    }

    public function login($request)
    {
        // 1º Passo -> Pegando credenciais
        $credentials = $request->all(['email', 'password']);

        $token = JWTAuth::attempt($credentials);

        // 2º Passo -> Autenticando e gerando token
        if ($token) {

            $resultado = $this->usersQuery->buscaInformacoes($credentials['email']); // Query responsável por buscar dados do usuário

            // Retornando resposta
            return ['resposta' => 'Autenticação realizada com sucesso!', 'usuario' => $resultado, 'token' => $token, 'status' => Response::HTTP_OK];
        }

        // Retornando caso usuário não seja encontrado
        return ['resposta' => 'Usuário ou senha inválidos!', 'usuario' => null, 'token' => null, 'status' => Response::HTTP_FORBIDDEN];
    }

    public function logout($request)
    {
        // 1º Passo -> Armazenando token
        $token = $request->input('token');

        // 2º Passo -> Coloca toke na blakclist
        $query = auth('api')->logout($token); // Colocando token na blacklist

        // 3º Passo -> Retorna respsota
        return ['resposta' => 'Logout realizado com sucesso!', 'status' => Response::HTTP_OK];
    }

    public function verificaToken()
    {
        return ['resposta' => 'O Token está válido!', 'status' => Response::HTTP_OK];
    }

}
