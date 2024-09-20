<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CotacaoRequest extends FormRequest
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
            'finalidade' => 'required|string',
            'rm' => 'integer',
            'id_comprador' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'finalidade.required' => 'O campo FINALIDADE é obrigatório!',
            'finalidade.string' => 'O campo FINALIDADE deve ser uma string!',
            'rm.integer' => 'O campo RM deve ser um inteiro!',
            'id_comprador.required' => 'O campo COMPRADOR é obrigatório!',
            'id_comprador.integer' => 'O campo COMPRADOR deve ser um inteiro!'
        ];
    }
}
