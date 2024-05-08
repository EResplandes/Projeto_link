<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedidoRequest extends FormRequest
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
            'valor' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'anexo' => 'required|file|mimes:pdf',
            'id_link' => 'required|integer',
            'id_empresa' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'descricao.required' => 'O campo DESCRIÇÂO é obrigatório!',
            'valor.required' => 'O campo VALOR é obrigatório!',
            'valor.regex' => 'O campo deve ser um número com até duas casas decimais!',
            'anexo.required' => 'O campo do arquivo PDF é obrigatório.',
            'anexo.file' => 'O campo deve ser um arquivo.',
            'anexo.mimes' => 'O arquivo deve ser um PDF.',
            'id_link.required' => 'O campo LINK é obrigatório!',
            'id_empresa' => 'O campo EMPRESA é obrigatório!'
        ];
    }
}
