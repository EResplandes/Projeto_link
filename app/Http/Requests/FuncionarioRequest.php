<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FuncionarioRequest extends FormRequest
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
            'nome' => 'required',
            'email' => 'required|email',
            'id_funcao' => 'required',
            'id_grupo' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo NOME é obrigatório!',
            'email.required' => 'O campo EMAIL é obrigatório',
            'email.email' => 'O campo EMAIL deve ser um e-mail valido!',
            'id_funcao.required' => 'O campo FUNÇÂO é obrigatório!',
            'id_grupo.required' => 'O campo GRUPO é obrigatório!'
        ];
    }
}
