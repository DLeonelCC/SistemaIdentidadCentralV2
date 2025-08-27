<?php

namespace App\Http\Requests\Sistema;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Validation\Rule;

class SistemaUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codigo' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sistemas', 'codigo')
                    ->ignore($this->route->parameter('sistema')),
            ],
            'nombre' => 'required|string|max:191',
        ];
    }
}
