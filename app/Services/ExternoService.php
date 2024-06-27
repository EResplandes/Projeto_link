<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;
use App\Models\Local;
use function PHPUnit\Framework\isEmpty;

class ExternoService
{

    public function listarGerentes()
    {
        // 1º Passo -> Buscar todos funcionários
        $query = UserResource::collection(User::where('id_funcao', 2)->get());

        // 2º Passo -> Retornar dados
        if ($query) {
            return ['resposta' => 'Funcionários listados com sucesso!', 'funcionarios' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function listarDiretores()
    {
        // 1º Passo -> Buscar todos funcionários
        $query = UserResource::collection(User::where('id_funcao', 3)->get());

        // 2º Passo -> Retornar dados
        if ($query) {
            return ['resposta' => 'Funcionários listados com sucesso!', 'funcionarios' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function listarPresidentes()
    {
        // 1º Passo -> Buscar todos funcionários
        $query = UserResource::collection(User::where('id_funcao', 5)->get());

        // 2º Passo -> Retornar dados
        if ($query) {
            return ['resposta' => 'Funcionários listados com sucesso!', 'funcionarios' => $query, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function listarEmpresas()
    {
        // 1º Passo -> Buscar todas empresas
        $empresas = Empresa::all();

        // 2º Passo -> Retornar resposta
        if ($empresas) {
            return ['resposta' => 'Empresas listados com sucesso!', 'empresas' => $empresas, 'status' => Response::HTTP_OK];
        } else {
            return ['resposta' => 'Occoreu algum problema, tente mais tarde!', 'status' => Response::HTTP_BAD_REQUEST];
        }
    }

    public function listarLocais()
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

    public function cadastrarPedido($request)
    {

        DB::beginTransaction();

        try {
            // 1º Passo -> Buscar id do funcionario logado atráves do e-mail passado
            $idCriador = User::where('email', $request->input('email'))->pluck('id')->first();

            // 2º Passo -> Salvar anexo do pedido
            $directory = "/pedidos"; // Criando diretório

            $pdf = $request->file('anexo')->store($directory, 'public'); // Salvando pdf do pedido

            // 2º Passo -> Montar array a ser inserido
            $idLink = $request->input('id_link');

            $idStatus = ($idLink == 2) ? 1 : 2;

            // 3º Passo -> Cadastrar pedido
            $dados = [
                'descricao' => $request->input('descricao'),
                'valor' => $request->input('valor'),
                'protheus' => 999,
                'urgente' => $request->input('urgente'),
                'dt_vencimento' => $request->input('dt_vencimento'),
                'anexo' => $pdf,
                'id_link' => $idStatus,
                'id_empresa' => $request->input('id_empresa'),
                'id_criador' => $idCriador,
                'id_local' => $request->input('id_local')
            ];

            // Verifica se pedido é com fluxo e sem fluxo para status e campo com fluxo
            if (isEmpty($request->input('fluxo'))) {
                $dados['tipo_pedido'] = 'Sem Fluxo';
                $dados['id_status'] = 6;
            } else {
                $dados['tipo_pedido'] = 'Com Fluxo';
                $dados['id_status'] = 7;
            }

            $queryPedido = Pedido::create($dados); // Cadastrando pedido

            $idPedido = $queryPedido->id; // Acessando id do pedido

            // 4º Passo -> Verificar se tem fluxo paara cadastro de fluxo
            if ($request->input('fluxo')) {
                $queryFluxo = $this->cadastroFluxo($request->input('fluxo'), $idPedido);
            }

            // 5º Passo -> Retornar resposta com o id do pedido
            DB::commit();
            return ['resposta' => 'Pedido cadastrado com sucesso!', 'pedido' => $idPedido, 'status' => Response::HTTP_CREATED];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'pedido' => null, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }

    public function cadastroFluxo($fluxo, $idPedido)
    {

        $fluxoArray = $fluxo; // Definiindo o array do fluxo

        $fluxoArray = json_decode($fluxoArray, true); // Transformando JSON em Array

        // Verificando se array foi preenchido
        if (empty($fluxoArray)) {
            return false;
        }

        // Insere os itens na tabela fluxos
        if ($fluxoArray != null || $fluxoArray != '') {
            foreach ($fluxoArray as $item) {
                DB::table('fluxos')->insert([
                    'id_usuario' => $item['id_usuario'],
                    'id_pedido' => $idPedido,
                    'assinado' => 0,
                ]);
            }
        } else {
            return false;
        }

        return true;
    }
}
