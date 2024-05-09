<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Status;

class StatusService
{
    public function listarStatus()
    {
        // 1º Passo -> Buscar todos status
        $query = Status::all();

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Status listados com sucesso!', 'itens' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }
}
