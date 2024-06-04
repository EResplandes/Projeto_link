<?php

namespace App\Services;

use Illuminate\Http\Response;
use App\Models\Boleto;
use Illuminate\Support\Facades\DB;
use App\Models\Pedido;

class BoletosService
{

    public function cadastrar($request, $id)
    {
        DB::beginTransaction();

        try {
            // 1º Passo -> Cadastra Boleto fiscal
            $directory = "/boletos"; // Criando diretório

            if ($request->file('boleto')) {
                $pdf = $request->file('boleto')
                    ->store($directory, 'public'); // Salvando pdf da boleto
            } else {
                return ['resposta' => 'O envio do  boleto é obrigatório!', 'status' => Response::HTTP_BAD_REQUEST];
            }

            // 2º Passo -> Cadastrar na tabela notas o registro
            $dados = [
                'boleto' => $pdf,
                'id_pedido' => $id
            ];

            Boleto::create($dados);

            // 3ª Passo -> Alterar status do pedido para enviado para fiscal
            Pedido::where('id', $id)->update(['id_status' => 15]);

            // 4º Passo -> Retornar resposta
            DB::commit();

            return ['resposta' => 'Boleto cadastrado com suceso!', 'status' => Response::HTTP_OK];
        } catch (\Exception $e) {
            DB::rollback(); // Se uma exceção ocorrer durante as operações do banco de dados, fazemos o rollback

            return ['resposta' => $e, 'status' => Response::HTTP_INTERNAL_SERVER_ERROR];

            throw $e;
        }
    }
}
