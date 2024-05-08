<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Empresa;

class EmpresaService
{
    public function listar()
    {
        // 1º Passo -> Buscar todas empresas
        $links = Empresa::all();

        // 2º Passo -> Retornar resposta
        if ($links) {
            return ['resposta' => 'Empresas listados com sucesso!', 'empresas' => $links, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }
}
