<?php

namespace App\Http\Requests\Proyectos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Validation\Rule;

class ProyectoUpdateRequest extends FormRequest
{
    protected $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

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
            'meta_codigo' => 'nullable|string|max:6',
            'codigo' => [
                'nullable',
                'string',
                'max:10',
                Rule::unique('proyectos', 'codigo')->ignore($this->route('proyecto')),
            ],
            'nombre' => 'required|max:500',
        ];
    }
}
