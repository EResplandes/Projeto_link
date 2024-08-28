<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ControleCaixaRequest extends FormRequest
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
            'id_caixa' => 'required|integer',
            'dt_lancamento' => 'required|date',
            'discriminacao' => 'required|string',
            'debito' => 'numeric',
            'credito' => 'numeric',
            'saldo' => 'numeric',
            'observacao' => 'string',
            'tipo_caixa' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'id_caixa.required' => 'O campo CAIXA é obrigatório!',
            'dt_lancamento.required' => 'O campo DATA DE LANCAMENTO é obrigatório!',
            'dt_lancamento.date' => 'O campo DATA DE LANCAMENTO deve ser uma data!',
            'discriminacao.required' => 'O campo DESCRIMINACAO é obrigatório!',
            'debito.numeric' => 'O campo DEBITO deve ser um número!',
            'credito.numeric' => 'O campo CREDITO deve ser um número!',
            'saldo.numeric' => 'O campo SALDO deve ser um número!',
            'observacao.string' => 'O campo OBSERVACAO deve ser uma string!',
            'tipo_caixa.required' => 'O campo TIPO CAIXA é obrigatório!',
            'tipo_caixa.string' => 'O campo TIPO CAIXA deve ser uma string!'
        ];
    }
}
