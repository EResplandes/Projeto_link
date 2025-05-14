<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImporteExcelRequest extends FormRequest
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
            'file' => 'required|mimes:xlsx,xls,csv',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'O arquivo e obrigatorio',
            'file.mimes' => 'O arquivo deve ser do tipo xlsx, xls ou csv',
        ];
    }
}
