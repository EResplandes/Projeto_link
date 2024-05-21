<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedidoSemFluxoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'descricao' => 'required',
            'valor' => 'required',
            'anexo' => 'required|file|mimes:pdf',
            'id_link' => 'required',
            'id_empresa' => 'required',
            'dt_vencimento' => 'required|date'

        ];
    }

    public function messages(): array
    {
        return [
            'descricao.required' => 'O campo DESCRIÇÃO é obrigatório!',
            'valor.required' => 'O campo VALOR é obrigatório!',
            'anexo.required' => 'É obrigatório o ANEXO do PDF!',
            'anexo.mimes' => 'O ANEXO deve ser do tipo PDF!',
            'id_link.required' => 'É obrigatório a seleção do link a qual será enviado!',
            'id_empresa.required' => 'É obrigatório a seleção da empresa onde o pedido foi cadastrado no Protheus',
            'dt_vencimento.required' => 'O campo Data de Vencimento é obrigatório!'
        ];
    }
}
