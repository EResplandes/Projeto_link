<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\Local;

class LocalService
{

    public function listar()
    {
        // 1ª Passo -> Listar todos locais
        $query = Local::all();

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Locais listados com sucesso!', 'locais' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }
}
