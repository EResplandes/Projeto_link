<?php

namespace App\Services;

use App\Http\Resources\CotacoesResource;
use App\Models\Cotacoes;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class CotacaoService
{

    public $apiKey = "b56a6143249170ba86f15c2c721c9b42522227c0ed5bcb80e2974bacd0ca4c3e";

    public function buscarPrecos($request)
    {
        // Obtenha o item da solicitação
        $item = $request->input('item');

        // Construa a URL dinamicamente
        $url = "https://serpapi.com/search.json?engine=google_shopping&q=" . urlencode($item) . "&location=Brazil&hl=pt&gl=br&api_key=" . $this->apiKey;

        // Crie uma instância do cliente Guzzle
        $client = new Client();

        // Faça uma solicitação GET para a API
        $response = $client->request('GET', $url);

        // Obtenha o corpo da resposta como uma string
        $body = $response->getBody();
        $data = json_decode($body, true);

        return [
            'resposta' => 'Itens listados com sucesso!',
            'resultados' => $data,
            'status' => Response::HTTP_OK
        ];
    }

    public function buscarCotacoes($id)
    {
        // 1º Passo -> Buscar todas cotações
        $query = CotacoesResource::collection(
            Cotacoes::where('id_comprador', $id)
                ->orderBy('id', 'desc')
                ->get()
        );

        // 2º Passo -> Retornar resposta
        if ($query) {
            return [
                'resposta' => 'Cotações listadas com sucesso!',
                'cotacoes' => $query,
                'status'   => Response::HTTP_OK
            ];
        } else {
            return [
                'resposta' => 'Ocorreu algum erro, entre em contato com o Administrador do Sistema!',
                'cotacoes' => null,
                'status'   => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }

    public function cadastrarCotacao($request)
    {
        // 1º Passo -> Montar array a ser inserido 
        $dados = array_filter([
            'id_comprador' => $request->id_comprador,
            'finalidade'   => $request->finalidade,
            'rm'           => $request->rm ?? null,
            'id_local'     => $request->id_local,
            'id_empresa'   => $request->id_empresa,
        ]);

        // 2º Passo -> Cadastrar Cotação
        $query = Cotacoes::create($dados);

        // 3º Passo -> Retornar resposta
        if ($query) {
            return [
                'resposta' => 'Cotação cadastrada com sucesso!',
                'status'   => Response::HTTP_CREATED
            ];
        } else {
            return [
                'resposta' => 'Ocorreu um erro, entre em contato com o Administrador!',
                'status'   => Response::HTTP_INTERNAL_SERVER_ERROR
            ];
        }
    }
}
