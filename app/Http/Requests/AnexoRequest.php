<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnexoRequest extends FormRequest
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
            'idLm' => 'required|integer',
            'anexo' => 'required',
            'observacapo' => 'string|requred'
        ];
    }

    public function messages()
    {
        return [
            'idLm.required' => 'O campo idLm é obrigatório.',
            'idLm.integer' => 'O campo idLm deve ser um inteiro.',
            'anexo.required' => 'O campo anexo é obrigatório.',
            'observacapo.required' => 'O campo observacapo é obrigatório.',
        ];
    }
}
