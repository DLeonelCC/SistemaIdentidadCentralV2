<?php

namespace App\Http\Requests\Persona;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;
use Illuminate\Validation\Rule;

class PersonaUpdateRequest extends FormRequest
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
            'tipo_doc' => 'required',
            'num_doc' => [
                'required',
                'string',
                'max:11',
                Rule::unique('personas', 'num_doc')
                    ->ignore($this->route->parameter('persona')),
            ],
            'nombre_completo' => 'nullable|max:573',
            'nombres' => 'required|max:191',
            'ap_paterno' => 'required|max:191',
            'ap_materno' => 'required|max:191',
            'email' => 'email|nullable',
            'direccion' => 'nullable|max:191',
            'celular' => 'nullable|size:9',
            'sexo' => 'required',
            'num_cuenta' => [
                'nullable',
                'string',
                'size:11',
                Rule::unique('personas', 'num_cuenta')
                    ->ignore($this->route->parameter('persona')),
            ],
            'ruc' => [
                'nullable',
                'string',
                'size:11',
                Rule::unique('personas', 'ruc')
                    ->ignore($this->route->parameter('persona')),
            ],
        ];
    }
}
