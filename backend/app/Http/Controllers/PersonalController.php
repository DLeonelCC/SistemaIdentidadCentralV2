<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersonalController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('America/Lima');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personals = Personal::leftJoin('personas', 'personals.persona_num_doc', '=', 'personas.num_doc')
            ->select('personals.*', 'personas.tipo_doc AS tipo_doc', 'personas.num_doc AS num_doc', 'personas.nombre_completo AS nombre_persona')
            ->orderBy(request('sort_by'), request('direction'));
        $personals->where(function ($q) {
            if (request()->has('codigo') && request('codigo') != '') {
                $q->where('personals.codigo', request('codigo'));
            };
        })->where(function ($q) {
            if (request()->has('tipo_doc') && request('tipo_doc') != '') {
                $q->where('personas.tipo_doc', request('tipo_doc'));
            };
        })->where(function ($q) {
            if (request()->has('num_doc') && request('num_doc') != '') {
                $q->where('personas.num_doc', request('num_doc'));
            };
        })->where(function ($q) {
            if (request()->has('tipo_contrato') && request('tipo_contrato') != '') {
                $q->where('personals.tipo_contrato', request('tipo_contrato'));
            };
        })->where(function ($q) {
            if (request()->has('sis_pen') && request('sis_pen') != '') {
                $q->where('personals.sis_pen', request('sis_pen'));
            };
        })->where(function ($q) {
            if (request()->has('condicion') && request('condicion') != '') {
                $q->where('personals.condicion', request('condicion'));
            };
        })->where(function ($q) {
            if (request()->has('search')) {
                $search = request('search');
                $q->where('personals.id', 'ILIKE', "%{$search}%")
                    ->orWhere('personals.codigo', 'ILIKE', "%{$search}%")
                    ->orWhere('personas.num_doc', 'ILIKE', "%{$search}%")
                    ->orWhere('personas.nombre_completo', 'ILIKE', "%{$search}%")
                    ->orWhere('personas.nombres', 'ILIKE', "%{$search}%")
                    ->orWhere('personas.ap_paterno', 'ILIKE', "%{$search}%")
                    ->orWhere('personas.ap_materno', 'ILIKE', "%{$search}%");
            }
        });
        $personals = $personals->paginate(request('page_size'));
        foreach ($personals->items() as &$item) {
            $item->append('persona');
            $item->append('user');
            $item->append('tenant');
        }
        return response($personals);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'tipo_doc' => 'required',
            'num_doc' => [
                'max:11',
                'string',
                'required',
                Rule::unique('personas', 'num_doc')->ignore($request->input('id_persona'))
            ],
            'nombre_completo' => 'required|max:573',
            'nombres' => 'required|max:191',
            'ap_paterno' => 'required|max:191',
            'ap_materno' => 'required|max:191',
            'direccion' => 'nullable|max:191',
            'celular' => 'size:9|required',
            'sexo' => 'required',
            'num_cuenta' => [
                'nullable',
                'string',
                'size:11',
                Rule::unique('personas', 'num_cuenta')->ignore($request->input('id_persona'))
            ],
            'codigo' => ['required', Rule::unique('personals', 'codigo')],
            'tipo_afp' => 'exclude_unless:sis_pen,AFP|required',
            'tip_com_seg' => 'exclude_unless:sis_pen,AFP|required',
        ]);

        if ($request->input('id_persona')) {
            $persona = Persona::find($request->input('id_persona'));
            $persona->update([
                'tipo_doc' => $request->tipo_doc,
                'num_doc' => $request->num_doc,
                'nombre_completo' => $request->nombre_completo,
                'nombres' => $request->nombres,
                'ap_paterno' => $request->ap_paterno,
                'ap_materno' => $request->ap_materno,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'celular' => $request->celular,
                'fec_nacimiento' => $request->fec_nacimiento,
                'sexo' => $request->sexo,
                'num_cuenta' => $request->num_cuenta,
                'nacionalidad' => $request->nacionalidad,
                'estado_civil' => $request->estado_civil,
                'ruc' => $request->ruc,
                'pais' => $request->pais,
                'ubigeo_actual' => $request->ubigeo_actual,
                'ubigeo_nacimiento' => $request->ubigeo_nacimiento,
            ]);
        } else {
            $persona = Persona::create([
                'tipo_doc' => $request->tipo_doc,
                'num_doc' => $request->num_doc,
                'nombre_completo' => $request->nombre_completo,
                'nombres' => $request->nombres,
                'ap_paterno' => $request->ap_paterno,
                'ap_materno' => $request->ap_materno,
                'email' => $request->email,
                'direccion' => $request->direccion,
                'celular' => $request->celular,
                'fec_nacimiento' => $request->fec_nacimiento,
                'sexo' => $request->sexo,
                'num_cuenta' => $request->num_cuenta,
                'nacionalidad' => $request->nacionalidad,
                'estado_civil' => $request->estado_civil,
                'ruc' => $request->ruc,
                'pais' => $request->pais,
                'ubigeo_actual' => $request->ubigeo_actual,
                'ubigeo_nacimiento' => $request->ubigeo_nacimiento,
            ]);
        }

        $personal = Personal::create([
            ...$request->all(),
            'persona_num_doc' => $persona->num_doc,
            'tenant_id' => $request->tenant_id,
        ]);
        $personal->persona = $persona;

        return response($personal, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $personal = Personal::select('personals.*')
            ->where('id', $id)
            ->first();
        $personal->append('persona');
        $personal->append('user');
        $personal->append('tenant');

        return response($personal);
    }

    /**
     * Display the specified resource.
     */
    public function showByDni(string $num_doc)
    {
        $personal = Personal::where('persona_num_doc', $num_doc)->first();
        return response($personal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        request()->validate([
            'tipo_doc' => 'required',
            'num_doc' => [
                'max:11',
                'string',
                'required',
                'unique:personas,num_doc,' . $request->input('id_persona')
            ],
            'nombre_completo' => 'required|max:573',
            'nombres' => 'required|max:191',
            'ap_paterno' => 'required|max:191',
            'ap_materno' => 'required|max:191',
            'direccion' => 'nullable|max:191',
            'celular' => 'size:9|required',
            'sexo' => 'required',
            'num_cuenta' => [
                'nullable',
                'string',
                'size:11',
                'unique:personas,num_cuenta,' . $request->input('id_persona')
            ],
            'codigo' => [
                'required',
                'unique:personals,codigo,' . $id
            ],
            'categoria_ocupacional' => 'required',
            'tipo_afp' => 'exclude_unless:sis_pen,AFP|required',
            'tip_com_seg' => 'exclude_unless:sis_pen,AFP|required',
        ]);

        $persona = Persona::find($request->input('id_persona'));
        $persona->update([
            'tipo_doc' => $request->tipo_doc,
            'num_doc' => $request->num_doc,
            'nombre_completo' => $request->nombre_completo,
            'nombres' => $request->nombres,
            'ap_paterno' => $request->ap_paterno,
            'ap_materno' => $request->ap_materno,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'celular' => $request->celular,
            'fec_nacimiento' => $request->fec_nacimiento,
            'sexo' => $request->sexo,
            'num_cuenta' => $request->num_cuenta,
            'nacionalidad' => $request->nacionalidad,
            'estado_civil' => $request->estado_civil,
            'ruc' => $request->ruc,
            'pais' => $request->pais,
            'ubigeo_actual' => $request->ubigeo_actual,
            'ubigeo_nacimiento' => $request->ubigeo_nacimiento,
        ]);

        $personal = Personal::find($id);
        $personal->update([
            ...$request->all(),
            'persona_num_doc' => $persona->num_doc,
            'tenant_id' => $request->tenant_id,
        ]);
        $personal->persona = $persona;

        return response($personal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response(Personal::destroy($id));
    }

    public function importarPersonal(Request $request)
    {
        $request->validate([
            'file' => ['required', 'mimes:csv,xlsx', 'max:8162'],
        ]);

        if ($request->hasFile('file')) {
            $filepath = $request->file('file');
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $reader->setReadDataOnly(true);
            $document = $reader->load($filepath);
            $data = $document->getSheet(0)->toArray(null, true, true, true);
            unset($reader);

            foreach ($data as $orden => $row) {
                if ($orden > 2 && preg_match("/[0-9]{8}/", $row['B']) == 1) {

                    $tipo_contrato = null;
                    if ($row['E'] == 1) {
                        $tipo_contrato = 'SERVIR 30057';
                    } else if ($row['E'] == 2) {
                        $tipo_contrato = 'NOMBRADO 276';
                    } else if ($row['E'] == 3) {
                        $tipo_contrato = 'CONTRATADO 276';
                    } else if ($row['E'] == 4) {
                        $tipo_contrato = 'PRIVADO 728';
                    } else if ($row['E'] == 5) {
                        $tipo_contrato = 'PRIVADO SC 728';
                    } else if ($row['E'] == 6) {
                        $tipo_contrato = 'CAS 1057';
                    } else if ($row['E'] == 7) {
                        $tipo_contrato = 'FAG';
                    }

                    $categoria_ocupacional = null;
                    if ($row['F'] == 1) {
                        $categoria_ocupacional = 'FUNCIONARIO';
                    } else if ($row['F'] == 2) {
                        $categoria_ocupacional = 'PROFESIONAL';
                    } else if ($row['F'] == 3) {
                        $categoria_ocupacional = 'TÉCNICO';
                    } else if ($row['F'] == 4) {
                        $categoria_ocupacional = 'AUXILIAR';
                    }

                    $sistema_pension = null;
                    if ($row['I'] == 1) {
                        $sistema_pension = 'AFP';
                    } else if ($row['I'] == 2) {
                        $sistema_pension = 'ONP';
                    } else if ($row['I'] == 3) {
                        $sistema_pension = 'NO AFIL.';
                    } else if ($row['I'] == 4) {
                        $sistema_pension = 'RETIRO_95.5';
                    }

                    $tipo_afp = null;
                    if ($row['J'] == 1) {
                        $tipo_afp = 'HÁBITAT';
                    } else if ($row['J'] == 2) {
                        $tipo_afp = 'INTEGRA';
                    } else if ($row['J'] == 3) {
                        $tipo_afp = 'PROFUTURO';
                    } else if ($row['J'] == 4) {
                        $tipo_afp = 'PRIMA';
                    }

                    $tipo_comision = null;
                    if ($row['L'] == 1) {
                        $tipo_comision = 'MIXTO';
                    } else if ($row['L'] == 2) {
                        $tipo_comision = 'FLUJO';
                    } else if ($row['L'] == 3) {
                        $tipo_comision = 'NINGUNO';
                    }

                    $condicion = null;
                    if ($row['T'] == 1) {
                        $condicion = 'ALTA';
                    } else if ($row['T'] == 2) {
                        $condicion = 'BAJA';
                    } else if ($row['T'] == 3) {
                        $condicion = 'PENDIENTE';
                    }

                    $fecha_i_excel = $row['G'];
                    $fecha_i = null;
                    if ($fecha_i_excel != null) {
                        $fecha_i = date('Y-m-d', strtotime("1899-12-30 +$fecha_i_excel days"));
                    }

                    $fecha_f_excel = $row['H'];
                    $fecha_f = null;
                    if ($fecha_f_excel != null) {
                        $fecha_f = date('Y-m-d', strtotime("1899-12-30 +$fecha_f_excel days"));
                    }

                    $persona = Persona::where('num_doc', $row['B'])->first();
                    if ($persona == null) {
                        Persona::create([
                            'tipo_doc' => $row['A'],
                            'num_doc' => $row['B'],
                            'datos_completos' => 0
                        ]);
                    };

                    $personal = Personal::where('persona_num_doc', $row['B'])->first();

                    if ($personal) {
                        $personal->update([
                            'codigo' => $row['C'],
                            'codigo_air' => $row['D'],
                            'fec_ini' => $fecha_i ?? $personal->fec_ini,
                            'fec_fin' => $fecha_f ?? $persona->fec_fin,
                            'tipo_contrato' => $tipo_contrato == null ? $personal->tipo_contrato : $tipo_contrato,

                            'cond_g_oc' => $row['M'] == null ? $personal->cond_g_oc : $row['M'],
                            'nivel_remunerativo' => $row['N'] == null ? $personal->nivel_remunerativo : $row['N'],
                            'tiempo_entidad' => $row['O'] == null ? $personal->tiempo_entidad : $row['O'],
                            'quinquenio' => $row['P'] == null ? $personal->quinquenio : $row['P'],
                            'rep_provincia' => $row['Q'] == null ? $personal->rep_provincia : $row['Q'],
                            'categoria_ocupacional' => $categoria_ocupacional == null ? $personal->categoria_ocupacional : $categoria_ocupacional,

                            'sis_pen' => $sistema_pension == null ? $personal->sis_pen : $sistema_pension,
                            'tipo_afp' => $tipo_afp == null ? $personal->tipo_afp : $tipo_afp,
                            'cuspp' => $row['K'] == null ? $personal->cuspp : $row['J'],
                            'tip_com_seg' => $tipo_comision == null ? $personal->tip_com_seg : $tipo_comision,

                            'discapacidad' => $row['R'] == 1 ? 1 : 0,
                            'sindicalizado' => $row['S'] == 1 ? 1 : 0,

                            'observacion' => $row['T'] == null ? $personal->observacion : $row['T'],
                            'condicion' => $condicion == null ? $personal->condicion : $condicion,

                            'persona_num_doc' => $row['B'],
                        ]);
                    } else {
                        $personal = Personal::create([
                            'codigo' => $row['C'],
                            'codigo_air' => $row['D'],
                            'fec_ini' => $fecha_i,
                            'fec_fin' => $fecha_f,
                            'tipo_contrato' => $tipo_contrato,

                            'cond_g_oc' => $row['M'],
                            'nivel_remunerativo' => $row['N'],
                            'tiempo_entidad' => $row['O'],
                            'quinquenio' => $row['P'],
                            'rep_provincia' => $row['Q'],
                            'categoria_ocupacional' => $categoria_ocupacional,

                            'sis_pen' => $sistema_pension,
                            'tipo_afp' => $tipo_afp,
                            'cuspp' => $row['K'],
                            'tip_com_seg' => $tipo_comision,

                            'discapacidad' => $row['R'] == 1 ? 1 : 0,
                            'sindicalizado' => $row['S'] == 1 ? 1 : 0,

                            'observacion' => $row['T'],
                            'condicion' => $condicion,

                            'persona_num_doc' => $row['B'],
                        ]);
                    }
                }
            }
            return $data;
        }
    }

    public function batch(Request $request)
    {
        $dnis = $request->input('dnis', []);

        if (empty($dnis)) {
            return response()->json([]);
        }

        // obtener todas las personas de una sola vez
        $items = Personal::whereIn('persona_num_doc', $dnis)->get();

        // transformar en objeto indexado por DNI
        $result = [];
        foreach ($items as $item) {
            $result[$item->num_doc] = [
                'id' => $item->id,
                'codigo' => $item->codigo,
                'codigo_air' => $item->codigo_air,
                'fec_ini' => $item->fec_ini,
                'fec_fin' => $item->fec_fin,
                'tipo_contrato' => $item->tipo_contrato,
                'cond_g_oc' => $item->cond_g_oc,
                'nivel_remunerativo' => $item->nivel_remunerativo,
                'tiempo_entidad' => $item->tiempo_entidad,
                'quinquenio' => $item->quinquenio,
                'rep_provincia' => $item->rep_provincia,
                'categoria_ocupacional' => $item->categoria_ocupacional,
                'sis_pen' => $item->sis_pen,
                'tipo_afp' => $item->tipo_afp,
                'cuspp' => $item->cuspp,
                'tip_com_seg' => $item->tip_com_seg,
                'discapacidad' => $item->discapacidad,
                'sindicalizado' => $item->sindicalizado,
                'observacion' => $item->observacion,
                'condicion' => $item->condicion,
                'persona_num_doc' => $item->persona_num_doc,
                'tenant_id' => $item->tenant_id,
            ];
        }

        return response()->json($result);
    }
}
