<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
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
            'nome_empresa' =>  'required',
            'cnpj' => 'required|unique:empresas',
            'inscricao_estadual' => 'required|unique:empresas',
            'filial' => 'integer'
        ];
    }

    public function messages()
    {
        return [
            'nome_empresa.required' => 'O campo EMPRESA é obrigatório!',
            'cnpj.required' => 'o campo CNPJ é obrigatório!',
            'cnpj.unique' => 'O CNPJ está sendo utilizado em outra empresa!',
            'inscricao_estadual.required' => 'O campo INSCRIÇÃO ESTADUAL é obrigatório!',
            'inscricao_estadual.unique' => 'A INSCRIÇÃO ESTADUAL está sendo utilizada em outra empresa!',
            'filial.integer' => 'O campo FILIAL deve ser um número!'
        ];
    }
}
