<?php

namespace App\Repositories;

use App\Http\Resources\AnexosResource;
use App\Http\Resources\ChatMateriaisResource;
use App\Http\Resources\HistoricoLmResource;
use App\Http\Resources\LmAlmoxarifadoResource;
use App\Http\Resources\LmResource;
use App\Models\Chat;
use App\Models\ChatLms;
use App\Models\ChatMateriais;
use App\Models\Funcao;
use App\Models\ListaMateriais;
use App\Models\HistoricoLm;
use App\Models\LancamentosMateriais;
use App\Models\LocaisLm;
use App\Models\MateriasLm;
use App\Models\StatusLm;
use App\Models\StatusMateriais;
use App\Models\User;
use App\Models\AnexosLm;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;

class LmRepositories
{

    public function cadastrarLm($request)
    {
        $hoje = \Carbon\Carbon::now();
        $prazo = \Carbon\Carbon::parse($request->prazo);
        $diasRestantes = $hoje->diffInDays($prazo, false); // false para poder retornar negativo se prazo já passou

        $urgente = ($diasRestantes < 7) ? 1 : 0; // 1 = urgente, 0 = padrão

        return ListaMateriais::create([
            'urgente' => $urgente,
            'lm' => strtoupper($request->lm),
            'aplicacao' => strtoupper($request->aplicacao),
            'prazo' => $request->prazo,
            'id_solicitante' => $request->id_solicitante,
            'id_status' => 7, // Validção de Quantitativo
            'id_empresa' => $request->id_empresa,
            'id_local' => $request->id_local
        ]);
    }

    public function cadastrarHistorico($request, $id_lm)
    {
        return HistoricoLm::create([
            'id_lm' => $id_lm,
            'observacao' => 'Lm nº ' . $id_lm . ' cadastrada.'
        ]); // Metódo responsável por cadastrar historico
    }

    public function mensagemLmCriada($request, $id_lm)
    {
        // Obtém o nome do usuário que criou a LM
        $nomeUsuario = User::where('id', $request->id_solicitante)->pluck('name')->first();

        // Cria a mensagem no ChatLms
        return ChatLms::create([
            'mensagem' => "A LM (ID: $id_lm) foi solicitada pelo usuário $nomeUsuario.",
            'id_lm' => $id_lm,
            'id_usuario' => 81
        ]); // Método responsável por cadastrar mensagem sobre a criação da LM
    }

    public function cadastrarMateriais($material, $id_lm)
    {
        return MateriasLm::create([
            'descricao' => strtoupper($material['descricao']),
            'descritiva' => strtoupper($material['descritiva']),
            'indicador' => strtoupper($material['indicador']),
            'unidade' => strtoupper($material['unidade']),
            'quantidade' => $material['quantidade'],
            'id_lm' => $id_lm
        ]); // Metódo responsável por cadastrar materiais
    }

    public function listarLms()
    {
        return LmResource::collection(ListaMateriais::orderBy('id', 'desc')->get()); // Metódo responsável por listar LMs
    }

    public function listarInformacoesComplementares()
    {
        return ListaMateriais::select('id_status', DB::raw('COUNT(*) as total'))
            ->groupBy('id_status')
            ->get(); // Metódo responsável por listar informações complementares
    }

    public function listarCompradores()
    {
        return User::whereIn('id_funcao', [6, 7])->get(); // Metódo responsável por listar compradores
    }

    public function associarComprador($idLm, $idComprador)
    {
        return ListaMateriais::where('id', $idLm)
            ->update([
                'id_comprador' => $idComprador,
                'id_status'    => 2
            ]); // Metódo responsável por associar um comprador a uma LM
    }

    public function cadastrarHistoricoAssociarComprador($idLm)
    {
        return HistoricoLm::create([
            'id_lm' => $idLm,
            'observacao' => 'Comprador associado.'
        ]); // Metódo responsável por cadastrar historico
    }

    public function lmAssociadas($idComprador)
    {
        return LmResource::collection(
            ListaMateriais::where('id_comprador', $idComprador)
                ->get()
        ); // Metódo responsável por listar LMs de um comprador atraves do id
    }

    public function associarPedido($idPedido, $idItem)
    {
        return MateriasLm::where('id', $idItem)
            ->update([
                'id_pedido' => $idPedido
            ]); // Metódo responsável por associar um item a um pedido
    }

    public function quantidadeMaterial($idMaterial)
    {
        return MateriasLm::where('id', $idMaterial)
            ->pluck('quantidade')
            ->first(); // Metódo responsável por buscar a quantidade de um material
    }

    public function cadastrarLancamento($request, $nota)
    {
        DB::beginTransaction();
        try {

            $usuario = User::where('id', $request->id_usuario)->pluck('name')->first();

            $mensagem = "O lançamento da nota fiscal número $request->numero_nota foi realizado pelo usuário $usuario. A quantidade de $request->quantidade_entregue itens.";

            ChatMateriais::create([
                'id_material' => $request->id_material,
                'id_usuario' => $request->id_usuario,
                'mensagem' => $mensagem
            ]);

            LancamentosMateriais::create([
                'id_material' => $request->id_material,
                'quantidade_entregue' => $request->quantidade_entregue,
                'dt_entrega' => $request->dt_entrega,
                'numero_nota' => $request->numero_nota,
                'nota' => $nota,
            ]); // Metódo responsável por cadastrar um lançamento
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function listarLancamentos($id)
    {
        return LancamentosMateriais::where('id_material', $id)
            ->get(); // Metódo responsável por listar todos lancamentos de um material
    }

    public function listarLocais()
    {
        return LocaisLm::all(); // Metódo responsável por listar todos locais
    }

    public function listarChat($idMaterial)
    {
        return ChatMateriaisResource::collection(
            ChatMateriais::where('id_material', $idMaterial)->get()
        );  // Metódo responsável por buscar chat
    }

    public function listarChatLm($idLm)
    {
        return ChatMateriaisResource::collection(
            ChatLms::where('id_lm', $idLm)->get()
        );  // Metódo responsável por buscar chat
    }

    public function enviarMensagem($request)
    {
        return ChatMateriais::create([
            'id_material' => $request->id_material,
            'id_usuario' => $request->id_usuario,
            'mensagem' => $request->mensagem
        ]); // Metódo responsável por enviar mensagem
    }

    public function enviarMensagemLm($request)
    {
        return ChatLms::create([
            'id_lm' => $request->id_lm,
            'id_usuario' => $request->id_usuario,
            'mensagem' => $request->mensagem
        ]); // Metódo responsável por enviar mensagem para uma lm
    }

    public function finalizarLm($idLm)
    {
        return ListaMateriais::where('id', $idLm)
            ->update([
                'id_status' => 6
            ]); // Metódo responsável por finalizar LM
    }

    public function mensagemCompradorAssociado($idLm, $idGerente, $idComprador)
    {

        $nomeComprador = User::where('id', $idComprador)->pluck('name')->first();
        $nomeGerente = User::where('id', $idGerente)->pluck('name')->first();

        return ChatLms::create([
            'id_lm' => $idLm,
            'id_usuario' => 81,
            'mensagem' => "O comprador(a) $nomeComprador foi vinculado(a) à LM pelo gerente $nomeGerente."
        ]); // Metódo responsável por enviar mensagem para uma lm
    }

    public function iniciarLm($idLm, $idComprador)
    {
        try {
            $comprador = User::where('id', $idComprador)->pluck('name')->first();

            ChatLms::create([
                'id_lm' => $idLm,
                'id_usuario' => 81,
                'mensagem' => "A LM de identificação $idLm foi iniciada pelo comprador $comprador."
            ]);

            return ListaMateriais::where('id', $idLm)
                ->update([
                    'id_comprador' => $idComprador,
                    'id_status' => 3
                ]); // Metódo responsável por iniciar LM

        } catch (\Exception $e) {
            return [
                'status' => 400,
                'erro' => $e->getMessage()
            ];
        }
    }

    public function listarStatusLm()
    {
        return StatusLm::whereIn('id', [3, 4])
            ->get(); // Metódo responsável por listar todos status
    }

    public function alterarStatusLm($idLm, $idStatus, $idComprador)
    {
        $comprador = User::where('id', $idComprador)->pluck('name')->first();
        $status = StatusLm::where('id', $idStatus)->pluck('status')->first();

        ChatLms::create([
            'id_lm' => $idLm,
            'id_usuario' => 81,
            'mensagem' => "O status da LM de identificação $idLm foi alterado para $status pelo usuário $comprador."
        ]);

        return ListaMateriais::where('id', $idLm)
            ->update([
                'id_status' => $idStatus,
            ]); // Metódo responsável por alterar status LM
    }

    public function alterarStatusMaterial($idMaterial, $idStatus, $idComprador)
    {
        $comprador = User::where('id', $idComprador)->pluck('name')->first();
        $status = StatusMateriais::where('id', $idStatus)->pluck('status')->first();

        $mensagem = "O status do material de identificação $idMaterial foi alterado para '$status' pelo comprador $comprador.";

        ChatMateriais::create([
            'id_material' => $idMaterial,
            'id_usuario' => 81, // Usuário que está registrando a alteração (provavelmente um sistema ou admin)
            'mensagem' => $mensagem
        ]);

        return MateriasLm::where('id', $idMaterial)
            ->update([
                'id_status' => $idStatus,
            ]); // Metódo responsável por alterar status do material
    }

    public function listarStatusMateriais()
    {
        return StatusMateriais::all(); // Metódo responsável por listar todos status
    }

    public function liberarMaterial($idMaterial, $idComprador)
    {
        try {

            $comprador = User::where('id', $idComprador)->pluck('name')->first();


            ChatMateriais::create([
                'id_material' => $idMaterial,
                'id_usuario' => 81,
                'mensagem' => "O material de identificação $idMaterial foi liberado pelo comprador $comprador para o almoxarife."
            ]);

            return MateriasLm::where('id', $idMaterial)
                ->update([
                    'liberado_almoxarife' => 1
                ]);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function bloquearMaterial($idMaterial, $idComprador)
    {
        try {

            $comprador = User::where('id', $idComprador)->pluck('name')->first();

            ChatMateriais::create([
                'id_material' => $idMaterial,
                'id_usuario' => 81,
                'mensagem' => "O material de identificação $idMaterial foi bloqueado pelo comprador $comprador."
            ]);

            return MateriasLm::where('id', $idMaterial)
                ->update([
                    'liberado_almoxarife' => 0
                ]);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function listarLmsAlmoxarifado()
    {
        return LmAlmoxarifadoResource::collection(ListaMateriais::all()); // Metódo responsável por listar LMs do almox
    }

    public function informacoesDashboard()
    {
        // 1º Passo -> Buscar lms por status
        $lmsPorStatus = ListaMateriais::select('id_status', DB::raw('count(*) as total'))
            ->groupBy('id_status')
            ->with('status') // Garante que o relacionamento será carregado
            ->get();

        // 2º Passo -> Buscar lms atrasadas
        $lmsAtrasadasPorStatus = ListaMateriais::where('prazo', '<', Carbon::now())
            ->count();

        // 3º Passo -> Buscar quantidade total de Lms
        $totalLms = ListaMateriais::count();

        // 4º Passo -> Buscar Lm por comprador
        $lmComprador = ListaMateriais::select('id_comprador', DB::raw('COUNT(*) as total'))
            ->groupBy('id_comprador')
            ->with('comprador') // garante que o nome do comprador será carregado
            ->get();

        // 5 Passo -> Buscar todos historicos de LM's
        $historicos = HistoricoLmResource::collection(HistoricoLm::limit(50)->get()); // Trabalhar com resourece

        return [
            'total_lms' => $totalLms,
            'total_lms_atrasadas' => $lmsAtrasadasPorStatus,
            'lms_por_comprador' => $lmComprador,
            'lms_por_status' => $lmsPorStatus,
            'historicos' => $historicos
        ];
    }

    public function cadastrarNovoMaterial($request)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Cadastrar Novo Material
            $cadastroMaterial = MateriasLm::create([
                'descricao' => strtoupper($request->descricao),
                "descritiva" => strtoupper($request->descritiva),
                "indicador" => strtoupper($request->indicador),
                'unidade' => strtoupper($request->unidade),
                'quantidade' => $request->quantidade,
                'id_lm' => $request->id_lm,
                'id_status' => 1
            ]);

            if (!$cadastroMaterial) {
                throw new \Exception('Falha ao cadastrar material');
            }

            // 2º Passo -> Gerar Histórico
            $chat = ChatLms::create([
                'id_lm' => $request->id_lm,
                'id_usuario' => 81,
                'mensagem' => "O material $request->descricao foi cadastrado pelo usuário $request->usuario"
            ]);

            if (!$chat) {
                throw new \Exception('Falha ao criar registro no chat');
            }

            // 3º Passo -> Alterar status da LM
            $alterarStatusLm = ListaMateriais::where('id', $request->id_lm)
                ->update([
                    'id_status' => 5,
                ]);

            if ($alterarStatusLm === 0) {
                throw new \Exception('Falha ao atualizar status da LM');
            }

            DB::commit();

            // 4º Passo -> Buscar Materias dessa LM
            return MateriasLm::where('id_lm', $request->id_lm)->get();
        } catch (\Exception $e) {
            DB::rollBack();
            // Log do erro para debugging
            dd($e);;
            \Log::error("Erro ao cadastrar material: " . $e->getMessage());
            return false; // Retorna false explicitamente em caso de erro
        }
    }

    public function validaFuncaoUsuario($id, $funcao)
    {
        DB::beginTransaction();

        try {

            $idFuncao = User::where('id', $id)->pluck('id_funcao')->first();

            $funcaoValidada = Funcao::where('id', $idFuncao)->pluck('funcao')->first();

            if ($funcao == $funcaoValidada) {
                return [
                    'funcao' => $funcaoValidada,
                    'acesso' => true
                ];
            } else {
                return [
                    'funcao' => $funcaoValidada,
                    'acesso' => false
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Erro: " . $e->getMessage());
            return false; // Retorna false explicitamente em caso de erro
        }
    }

    public function listarAnexos($idLM)
    {
        return AnexosResource::collection(
            AnexosLm::where('id_lm', $idLM)
                ->get()
        );
    }

    public function salvarAnexo($request, $caminhoAnexo, $extensao)
    {

        DB::beginTransaction();
        try {

            $queryInsertAnexo = AnexosLm::create([
                'id_lm' => $request->id_lm,
                'id_usuario' => $request->id_usuario,
                'anexo' => $caminhoAnexo,
                'observacao' => $request->observacao,
                'extensao' => $extensao

            ]);

            if (!$queryInsertAnexo) {
                throw new \Exception('Falha ao salvar anexo');
            }

            // Cria a mensagem no ChatLms
            $queryInsertChat = ChatLms::create([
                'mensagem' => "Foi adicionado um novo anexo pelo usuário $request->usuario, com a seguinte observação: $request->observacao",
                'id_lm' => $request->id_lm,
                'id_usuario' => 81 // Usuário de LOG
            ]); // Método responsável por cadastrar mensagem sobre a criação da LM

            if (!$queryInsertChat) {
                throw new \Exception('Falha ao criar registro no chat');
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Erro: " . $e->getMessage());
            return false; // Retorna false explicitamente em caso de erro
        }
    }

    public function autorizarQuantidade($request)
    {

        DB::beginTransaction();

        try {
            $materiais = $request->input('materiais');

            if (is_string($materiais)) {
                $materiais = json_decode($materiais, true);
            }


            if (is_array($materiais)) {
                foreach ($materiais as $material) {
                    MateriasLm::where('id', $material['id'])
                        ->update(['quantidade_autorizada' => $material['quantidade_autorizada']]);
                }
            }

            $idLm = MateriasLm::where('id', $materiais[0]['id'])->pluck('id_lm')->first();

            DB::table('materiais_lm')
                ->where('id_lm', $idLm)
                ->whereNull('quantidade_autorizada')
                ->update([
                    'quantidade_autorizada' => DB::raw('quantidade')
                ]);

            $novosMateriais = MateriasLm::where('id_lm', $idLm)->get();

            ChatLms::create([
                'mensagem' => "Quantidade de materiais validada pelo usuário $request->usuario",
                'id_lm' => $idLm,
                'id_usuario' => 81
            ]);

            ListaMateriais::where('id', $idLm)
                ->update([
                    'id_status' => 1
                ]);

            DB::commit();

            if ($idLm) {
                return [
                    'materiais' => $novosMateriais,
                    'status' => true
                ];
            } else {
                return [
                    'materiais' => $novosMateriais,
                    'status' => false
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Erro: " . $e->getMessage());
            return false; // Retorna false explicitamente em caso de erro
        }
    }
}
