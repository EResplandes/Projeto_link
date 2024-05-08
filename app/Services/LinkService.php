<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Queries\UsersQuery;
use App\Models\Link;

class LinkService
{

    public function listar()
    {
        // 1º Passo -> Buscar todos links
        $links = Link::all();

        // 2º Passo -> Retornar resposta
        if ($links) {
            return ['resposta' => 'Links listados com sucesso!', 'links' => $links, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }
}
