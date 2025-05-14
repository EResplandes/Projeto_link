<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Repositories\LmRepositories;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MateriaisLmImport;

class LmService
{

    protected $lmRepositories;

    public function __construct(LmRepositories $lmRepositories)
    {
        $this->lmRepositories = $lmRepositories;
    }

    public function listarLms()
    {
        try {

            // 1º Passo -> Buscar todas Lm's
            $query = $this->lmRepositories->listarLms(); // Metódo responsável por listar LMs

            // 2º Passo -> Buscar Informações complementares
            $informacoes = $this->lmRepositories->listarInformacoesComplementares();

            // 3º Passo -> Retornar resposta
            return [
                'status' => Response::HTTP_OK,
                'lm' => $query,
                'informacoes' => $informacoes
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function cadastrarLm($request)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Cadastrar LM
            $queryCadastroLm = $this->lmRepositories->cadastrarLm($request);

            // 2º Passo -> Cadastrar historico
            $queryCadastroHistorico = $this->lmRepositories->cadastrarHistorico($request, $queryCadastroLm->id);

            $queryCadastroMensagem = $this->lmRepositories->mensagemLmCriada($request, $queryCadastroLm->id);

            // 3º Passo -> Cadastrar materias da LM
            $queryCadastrarMateriais = Excel::import(new MateriaisLmImport($queryCadastroLm->id), $request->file('materiais'));

            // 4º Pasoo -> Retornar resposta
            DB::commit();

            return [
                'status' => Response::HTTP_OK,
                'resposta' => 'LM cadastrada com sucesso',
            ];
        } catch (\Exception $e) {
            DB::rollback(); // Se der erro, ele volta para o estado inicial

            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'resposta' => 'Erro ao cadastrar LM',
                'erro' => $e->getMessage()
            ];
        }
    }

    public function listarCompradores()
    {
        try {

            // 1º Passo -> Buscar todas compradores
            $query = $this->lmRepositories->listarCompradores();

            // 2º Passo -> Retornar resposta
            return [
                'status' => Response::HTTP_OK,
                'compradores' => $query
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function associarComprador($idLm, $idComprador, $idGerente)
    {
        try {

            // 1º Passo -> Associar comprador a LM
            $queryAssociarComprador = $this->lmRepositories->associarComprador($idLm, $idComprador);

            // 2º Passo -> Gerar Histórico
            $queryCriarHistorico = $this->lmRepositories->cadastrarHistoricoAssociarComprador($idLm);

            // 3º Passo -> Inserir informação no chat
            $queryInserirInformacaoNoChat = $this->lmRepositories->mensagemCompradorAssociado($idLm, $idGerente, $idComprador);

            // 3º Passo -> Retornar resposta
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'resposta' => 'Comprador associado com sucesso'
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function lmAssociadas($idComprador)
    {
        try {
            // 1º Passo -> Buscar LM's associadas ao comprador
            $query = $this->lmRepositories->lmAssociadas($idComprador);

            // 2º Passo -> Retornar resposta
            return [
                'status' => Response::HTTP_OK,
                'lm' => $query
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function associarPedido($idPedido, $idItem)
    {
        DB::beginTransaction();
        try {
            // 1º Passo -> Associar pedido ao item da LM
            $query = $this->lmRepositories->associarPedido($idPedido, $idItem);

            // 2º Passo -> Retornar resposta
            DB::commit();

            return [
                'status' => Response::HTTP_OK,
                'resposta' => 'Pedido associado com sucesso'
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function cadastrarLancamento($request)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Salvar Nota Fiscal
            $ano = date('Y'); // Ano atual
            $mes = date('m'); // Mês atual

            $directory = "/lms/{$ano}/{$mes}/$request->id_material"; // Criando diretório ano/mês

            $pdf = $request->file('nota')->store($directory, 'public'); // Salvando pdf da nota fiscal

            // 2º Passo -> Verificar se a quantidade lancamento e igual ao material
            $quantidadeMaterial = $this->lmRepositories->quantidadeMaterial($request->id_material);

            if ($request->quantidade_entregue > $quantidadeMaterial) {

                return [
                    'status' => Response::HTTP_BAD_REQUEST,
                    'erro' => 'Quantidade entregue maior que a quantidade do pedido'
                ];
            }

            // 3º Passo -> Cadastrar Lancamento
            $query = $this->lmRepositories->cadastrarLancamento($request, $pdf);

            // 4º Passo -> Retornar resposta
            DB::commit();

            return [
                'status' => Response::HTTP_OK,
                'resposta' => 'Lancamento cadastrado com sucesso'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function listarLancamentos($idMaterial)
    {
        try {
            // 1º Passo -> Buscar todos lancamentos de um material
            $query = $this->lmRepositories->listarLancamentos($idMaterial);

            // 2º Passo -> Retornar resposta
            return [
                'status' => Response::HTTP_OK,
                'lancamentos' => $query
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function listarLocais()
    {
        try {
            // 1 ª Passo -> Buscar todos locais
            $query = $this->lmRepositories->listarLocais();

            // 2º Passo -> Retornar resposta
            return [
                'status' => Response::HTTP_OK,
                'locais' => $query
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function listarChat($idMaterial)
    {
        try {
            // 1º Passo -> Buscar todos chat
            $query = $this->lmRepositories->listarChat($idMaterial);

            // 2º Passo -> Retornar resposta
            return [
                'status' => Response::HTTP_OK,
                'chat' => $query
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function enviarMensagem($request)
    {
        try {
            // 1º Passo -> Cadastrar Mensagem
            $query = $this->lmRepositories->enviarMensagem($request);

            // 2º Passo -> Retornar resposta
            return [
                'status' => Response::HTTP_OK,
                'resposta' => 'Mensagem enviada com sucesso'
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function enviarMensagemLm($request)
    {
        try {
            // 1º Passo -> Cadastrar Mensagem
            $query = $this->lmRepositories->enviarMensagemLm($request);

            // 2º Passo -> Retornar resposta
            return [
                'status' => Response::HTTP_OK,
                'resposta' => 'Mensagem enviada com sucesso'
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function finalizarLm($idLm)
    {
        DB::beginTransaction();
        try {

            // 1º Passo -> Finalizar LM
            $query = $this->lmRepositories->finalizarLm($idLm);

            // 2º Passo -> Retornar resposta
            DB::commit();

            return [
                'status' => Response::HTTP_OK,
                'resposta' => 'LM finalizada com sucesso'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function listarChatLm($idLm)
    {
        try {

            // 1º Passo -> Buscar todos chat
            $query = $this->lmRepositories->listarChatlM($idLm);

            // 2º Passo -> Retornar resposta
            return [
                'status' => Response::HTTP_OK,
                'chat' => $query
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function iniciarLm($idLm, $idComprador)
    {
        DB::beginTransaction();
        try {

            // 1º Passo -> Iniciar LM
            $query = $this->lmRepositories->iniciarLm($idLm, $idComprador);

            // 2º Passo -> Retornar resposta
            DB::commit();

            return [
                'status' => Response::HTTP_OK,
                'resposta' => 'LM iniciada com sucesso'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function listarStatusLm()
    {
        try {

            // 1º Passo -> Buscar todos status LM
            $query = $this->lmRepositories->listarStatusLm();

            // 2º Passo -> Retornar resposta
            return [
                'status' => Response::HTTP_OK,
                'statusLm' => $query
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function alterarStatusLm($idLm, $idStatus, $idComprador)
    {
        DB::beginTransaction();
        try {

            // 1º Passo -> Alterar status LM
            $query = $this->lmRepositories->alterarStatusLm($idLm, $idStatus, $idComprador);

            // 2º Passo -> Retornar resposta

            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'resposta' => 'Status LM alterado com sucesso'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function listarStatusMateriais()
    {
        try {
            // 1º Passo -> Buscar todos status LM
            $query = $this->lmRepositories->listarStatusMateriais();

            // 2º Passo -> Retornar resposta
            return [
                'status' => Response::HTTP_OK,
                'statusMateriais' => $query
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function alterarStatusMaterial($idMaterial, $idStatus, $idComprador)
    {
        DB::beginTransaction();
        try {
            // 1º Passo -> Alterar status LM
            $query = $this->lmRepositories->alterarStatusMaterial($idMaterial, $idStatus, $idComprador);

            // 2º Passo -> Retornar resposta
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'resposta' => 'Status LM alterado com sucesso'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function liberarMaterial($idMaterial, $idComprador)
    {
        DB::beginTransaction();
        try {
            // 1º Passo -> Alterar status LM
            $query = $this->lmRepositories->liberarMaterial($idMaterial, $idComprador);

            // 2º Passo -> Retornar resposta
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'resposta' => 'Material liberado com sucesso'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function bloquearMaterial($idMaterial, $idComprador)
    {
        DB::beginTransaction();
        try {
            // 1º Passo -> Alterar status LM
            $query = $this->lmRepositories->bloquearMaterial($idMaterial, $idComprador);

            // 2º Passo -> Retornar resposta
            DB::commit();
            return [
                'status' => Response::HTTP_OK,
                'resposta' => 'Material bloqueado com sucesso'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function listarLmsAlmoxarifado()
    {
        try {
            // 1º Passo -> Buscar todos status LM
            $query = $this->lmRepositories->listarLmsAlmoxarifado();

            // 2º Passo -> Retornar resposta
            return [
                'status' => Response::HTTP_OK,
                'lms' => $query
            ];
        } catch (\Exception $e) {
            return [
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'erro' => $e->getMessage()
            ];
        }
    }
}
