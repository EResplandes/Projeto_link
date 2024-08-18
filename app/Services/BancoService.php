<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Banco;
use Illuminate\Support\Facades\DB;

class BancoService
{

    public function listar()
    {
        $query = Banco::all(); // Metódo responsável por buscar todos bancos

        if ($query) {
            return [
                'resposta'  => 'Bancos listados com sucesso!',
                'bancos'    => $query,
                'status'    => Response::HTTP_OK,
            ];
        } else {
            return [
                'resposta'  => 'Ocorreu algum problema, tente mais tarde!',
                'bancos'    => null,
                'status'    => Response::HTTP_BAD_REQUEST,
            ];
        }
    }
}
