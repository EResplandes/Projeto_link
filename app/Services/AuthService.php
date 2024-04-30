<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use DateTime;


class AuthService
{

    public function login($request)
    {

        $credenciais = $request->all(['email', 'password']);
        $email = $request->input('email');

        $token = auth('api')->attempt($credenciais); // Verificando se o usuário existe

        if ($token == false) {
            $token = 'Usuário ou senha inválidos!';
            return $token;
        } else {

            // Pegando informações do usuário
            $information = DB::table('users')
                ->join('companies', 'users.fk_companie', '=', 'companies.id')
                ->select(
                    'users.id',
                    'users.name AS user_name',  // Alias para o campo 'name' da tabela 'users'
                    'users.email',
                    'users.cpf',
                    'users.status',
                    'users.first_login',
                    'companies.id AS company_id',
                    'companies.name AS company_name'  // Alias para o campo 'name' da tabela 'companies'
                )
                ->where('email', $email)
                ->get();

            // Verificando se tem registro
            if ($information->count() > 0) {

                $firstItem = $information->first(); // Obtenha o primeiro item
                $userId = $firstItem->id;           // Acesse o id do primeiro item
            }

            // Pegando permissões do usuário
            $permissions = DB::table('users_permissions')
                ->join('permissions', 'users_permissions.fk_permission', '=', 'permissions.id')
                ->select('permissions.slug')
                ->where('fk_user', $userId)->get();

            return ['Token' => $token, 'User' => $information, 'Permissions' => $permissions]; // Retornando resposta para a requisição
        }
    }

    public function logout($request)
    {

        $token = $request->input('token'); // Armazenando token

        $query = auth('api')->logout($token); // Colocando token na blacklist

        return $query;
    }


}
