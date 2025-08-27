<?php

namespace App\Http\Requests\archivos;

use Illuminate\Foundation\Http\FormRequest;

class ArchivoUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'tipo' => 'required',
            'tipo_doc' => 'required',
            'fecha' => 'required',
            'descripcion' => 'required',
        ];
    }
}
