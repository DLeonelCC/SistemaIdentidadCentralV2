<?php

namespace App\Http\Requests\personal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;

class PersonalUpdateRequest extends FormRequest
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
            'tipo_consulta' => 'nullable',
            'condicion' => 'exclude_unless:tipo_consulta,ALTA|required',
            'fec_alta' =>  'exclude_unless:tipo_consulta,ALTA|required',
            'fec_baja' => 'exclude_unless:condicion,BAJA|required',
            'motivo_baja' => 'exclude_unless:condicion,BAJA|required',
            'persona_num_doc' => ['required', 'string', 'max:8', 'unique:personals,persona_num_doc,' . $this->route->parameter('personal')],
            'codigo' => ['exclude_unless:tipo_consulta,ASISTENCIA', 'required', 'unique:personals,codigo,' . $this->route->parameter('personal')],
            'sis_pen' => 'required',
            'tipo_afp' => 'exclude_unless:sis_pen,AFP|required',
            'cuspp' => 'exclude_unless:sis_pen,AFP|required',
            'tipo_com_seg' => 'exclude_unless:sis_pen,AFP|required',
        ];
    }
}
