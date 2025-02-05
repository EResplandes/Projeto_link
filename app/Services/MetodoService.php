<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\MetodoPagamento;

class MetodoService
{

    public function listar()
    {
        // 1ª Passo -> Listar todos locais
        $query = MetodoPagamento::all();

        // 2º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Metodos listados com sucesso!', 'metodos' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }
}
