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

    public function cadastrar($request)
    {
        // 1º Passo -> Montar array a ser inserido
        $dados = [
            'nome_empresa' => $request->input('nome_empresa'),
            'cnpj' => $request->input('cnpj'),
            'inscricao_estadual' => $request->input('inscricao_estadual'),
            'filial' => $request->input('filial')
        ];

        // 2º Passo -> Cadastrar empresa no banco
        $query = Empresa::create($dados);

        // 3º Passo -> Retornanr resposta
        if ($query) {
            return ['resposta' => 'Empresa cadastrada com sucesso!', 'status' => Response::HTTP_CREATED];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function deletar($id)
    {
        // 1º Passo -> Deletar empresa de acordo com id
        $query = Empresa::where('id', $id)->delete();

        // 2 º Passo -> Retornar resposta
        if ($query) {
            return ['resposta' => 'Empresa deletada com sucesso!', 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }
}
