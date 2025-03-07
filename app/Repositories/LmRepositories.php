<?php

namespace App\Repositories;

use App\Http\Resources\ChatMateriaisResource;
use App\Http\Resources\LmAlmoxarifadoResource;
use App\Http\Resources\LmResource;
use App\Models\Chat;
use App\Models\ChatLms;
use App\Models\ChatMateriais;
use App\Models\ListaMateriais;
use App\Models\HistoricoLm;
use App\Models\LancamentosMateriais;
use App\Models\LocaisLm;
use App\Models\MateriasLm;
use App\Models\StatusLm;
use App\Models\StatusMateriais;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LmRepositories
{

    public function cadastrarLm($request)
    {
        return ListaMateriais::create([
            'urgente' => $request->urgente,
            'lm' => strtoupper($request->lm),
            'aplicacao' => $request->aplicacao,
            'prazo' => $request->prazo,
            'id_solicitante' => $request->id_solicitante,
            'id_status' => 1,
            'id_empresa' => $request->id_empresa,
            'id_local'    => $request->id_local
        ]); // Metódo responsável por cadastrar LM
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
            'descricao' => $material['descricao'],
            'unidade' => $material['unidade'],
            'quantidade' => $material['quantidade'],
            'id_lm' => $id_lm
        ]); // Metódo responsável por cadastrar materiais
    }

    public function listarLms()
    {
        return LmResource::collection(ListaMateriais::all()); // Metódo responsável por listar LMs
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
}
