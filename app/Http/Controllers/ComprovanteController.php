<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Process;

class ComprovanteController extends Controller
{
    public function abreExplore(Request $request)
    {
        // 1º Passo -> Pegar diretório onde comprovante está salvo
        $diretorio = Pedido::where('id', $request->input('id_pedido'))->pluck('comprovante')->first();

        $ipAddress = $request->ip();

        dd($ipAddress = $request->ip());

        $result = Process::run("'\\\\server1\FILESERVER\05 FINANCEIRO$\01 - Comprovantes de Pagamentos (Fornecedores)\COMPARTILHAMENTO CLOUD\\' . $diretorio");

        return $result;
        // // 1º Passo -> Pegar diretório onde comprovante está salvo
        // $diretorio = Pedido::where('id', $id)->pluck('comprovante')->first();

        // // 2º Passo -> Abrir pasta
        // if ($diretorio) {
        //     // Usar o ponto (.) para concatenação de strings
        //     $path = '\\\\server1\FILESERVER\05 FINANCEIRO$\01 - Comprovantes de Pagamentos (Fornecedores)\COMPARTILHAMENTO CLOUD\\' . $diretorio;
        //     $process = new Process(['explorer', $path]);
        //     // dd($process);

        //     try {
        //         $process->mustRun();
        //         return response()->json(['resposta' => 'Explore aberto com sucesso!'], 200);
        //     } catch (ProcessFailedException $exception) {
        //         return response()->json(['message' => 'Ocorreu algum erro, entre em contato com o Administrador!', 'error' => $exception->getMessage()], 500);
        //     }
        // } else {
        //     return response()->json(['message' => 'Diretório não encontrado'], 400);
        // }
    }
}
