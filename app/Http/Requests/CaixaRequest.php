<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaixaRequest extends FormRequest
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
            'funcionario' => 'required|string',
            'funcao_funcionario' => 'required|string',
            'anexo' => 'file',
            'banco' => 'required',
            'agencia' => 'required|integer',
            'conta' => 'required|integer',
            'cpf' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'funcionario.required' => 'O campo FUNCIONÁRIO é obrigatório!',
            'funcionario.string' => 'O campo FUNCIONÁRIO deve ser uma string!',
            'funcao_funcionario.required' => 'O campo FUNCAO DO FUNCIONÁRIO é obrigatório!',
            'funcao_funcionario.string' => 'O campo FUNCAO DO FUNCIONÁRIO deve ser uma string!',
            'banco.required' => 'O campo BANCO é obrigatório!',
            'agencia.required' => 'O campo AGENÇA é obrigatório!',
            'agencia.integer' => 'O campo AGENÇA deve ser um inteiro!',
            'conta.required' => 'O campo CONTA é obrigatório!',
            'conta.integer' => 'O campo CONTA deve ser um inteiro!',
            'cpf.required' => 'O campo CPF é obrigatório!',
            'cpf.integer' => 'O campo CPF deve ser um inteiro!'
        ];
    }
}
