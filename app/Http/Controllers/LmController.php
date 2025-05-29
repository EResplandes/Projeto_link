<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LmService;

class LmController extends Controller
{

    protected $lmService;

    public function __construct(LmService $lmService)
    {
        $this->lmService = $lmService;
    }

    public function listarLms()
    {
        $query = $this->lmService->listarLms(); // Metódo responsável por listar LMs
        return response()->json($query);
    }

    public function cadastrarLm(Request $request)
    {
        $query = $this->lmService->cadastrarLm($request); // Metódo responsável por cadastrar LM
        return response()->json($query);
    }

    public function listarCompradores()
    {
        $query = $this->lmService->listarCompradores(); // Metódo responsável por buscar compradores
        return response()->json($query);
    }

    public function associarComprador($idLm, $idComprador, $idGerente)
    {
        $query = $this->lmService->associarComprador($idLm, $idComprador, $idGerente); // Metódo responsável por associar um comprador a uma LM 
        return response()->json($query);
    }

    public function lmAssociadas($idComprador)
    {
        $query = $this->lmService->lmAssociadas($idComprador); // Metódo responsável por buscar LM's associadas a um comprador
        return response()->json($query);
    }

    public function associarPedido($idPedido, $idItem)
    {
        $query = $this->lmService->associarPedido($idPedido, $idItem); // Metódo responsável por associar um item a um pedido
        return response()->json($query);
    }

    public function cadastrarLancamento(Request $request)
    {
        $query = $this->lmService->cadastrarLancamento($request); // Metódo responsável por cadastrar um lançamento
        return response()->json($query);
    }

    public function listarLancamentos($idMaterial)
    {
        $query = $this->lmService->listarLancamentos($idMaterial); // Metódo responsável por listar todos lancamentos de um material
        return response()->json($query);
    }

    public function listarLocais()
    {
        $query = $this->lmService->listarLocais(); // Metódo responsável por listar todos locais
        return response()->json($query);
    }

    public function listarChat($idMaterial)
    {
        $query = $this->lmService->listarChat($idMaterial); // Metódo responsável por buscar chat
        return response()->json($query);
    }

    public function enviarMensagem(Request $request)
    {
        $query = $this->lmService->enviarMensagem($request); // Metódo responsável por enviar mensasgem
        return response()->json($query);
    }

    public function finalizarLm($idLm)
    {
        $query = $this->lmService->finalizarLm($idLm); // Metódo responsável por finalizar LM
        return response()->json($query);
    }

    public function listarChatLm($idLm)
    {
        $query = $this->lmService->listarChatLm($idLm); // Metódo responsável por buscar chat da LM
        return response()->json($query);
    }

    public function enviarMensagemLm(Request $request)
    {
        $query = $this->lmService->enviarMensagemLm($request); // Metódo responsável por enviar mensagem da LM
        return response()->json($query);
    }

    public function iniciarLm($idLm, $idComprador)
    {
        $query = $this->lmService->iniciarLm($idLm, $idComprador); // Metódo responsável por iniciar LM
        return response()->json($query);
    }

    public function listarStatusLm()
    {
        $query = $this->lmService->listarStatusLm(); // Metódo responsável por listar status LM
        return response()->json($query);
    }

    public function alterarStatusLm($idLm, $idStatus, $idComprador)
    {
        $query = $this->lmService->alterarStatusLm($idLm, $idStatus, $idComprador); // Metódo responsável por alterar status LM
        return response()->json($query);
    }

    public function listarStatusMateriais()
    {
        $query = $this->lmService->listarStatusMateriais(); // Metódo responsável por listar status materiais
        return response()->json($query);
    }

    public function alterarStatusMaterial($idMaterial, $idStatus, $idComprador)
    {
        $query = $this->lmService->alterarStatusMaterial($idMaterial, $idStatus, $idComprador); // Metódo responsável por alterar status material
        return response()->json($query);
    }

    public function liberarMaterial($idMaterial, $idComprador)
    {
        $query = $this->lmService->liberarMaterial($idMaterial, $idComprador); // Metódo responsável por liberar material
        return response()->json($query);
    }

    public function bloquearMaterial($idMaterial, $idComprador)
    {
        $query = $this->lmService->bloquearMaterial($idMaterial, $idComprador); // Metódo responsável por bloquear material
        return response()->json($query);
    }

    public function listarLmsAlmoxarifado()
    {
        $query = $this->lmService->listarLmsAlmoxarifado(); // Metódo responsável por listar lms do almoxarifado
        return response()->json($query);
    }

    public function informacoesDashboard()
    {
        $query = $this->lmService->informacoesDashboard(); // Metódo responsável por buscar todas informações que compôe o dashboard
        return response()->json($query);
    }

    public function cadastrarNovoMaterial(Request $request)
    {
        $query = $this->lmService->cadastrarNovoMaterial($request); // Metódo responsável por cadastrar um novo item a uma nova lista de material após a sua criação
        return response()->json($query);
    }
}
