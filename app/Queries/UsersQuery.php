<?php

namespace App\Queries;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;

class UsersQuery
{
    public function buscaInformacoes($email)
    {
        // 1º Passo -> Busca id do usuário
        $idUsuario = User::where('email', $email)
            ->value('id');

        // 2º Passo -> Query responsável por buscar dados do usuário
        $query = UserResource::collection(User::where('id', $idUsuario)->get());

        // 3º Passo -> retorna resposta
        return $query;
    }
}
