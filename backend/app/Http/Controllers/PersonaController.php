<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Persona\PersonaStoreRequest;
use App\Http\Requests\Persona\PersonaUpdateRequest;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PersonaController extends Controller
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
        $personas = Persona::orderBy(request('sort_by'), request('direction'));
        $personas->where(function ($q) {
            if (request()->has('tipo_doc') && request('tipo_doc') != '') {
                $q->where('tipo_doc', request('tipo_doc'));
            };
        })->where(function ($q) {
            if (request()->has('num_doc') && request('num_doc') != '') {
                $q->where('num_doc', request('num_doc'));
            };
        })->where(function ($q) {
            if (request()->has('sexo') && request('sexo') != '') {
                $q->where('sexo', request('sexo'));
            };
        })->where(function ($q) {
            if (request()->has('estado_civil') && request('estado_civil') != '') {
                $q->where('estado_civil', request('estado_civil'));
            };
        })->where(function ($q) {
            if (request()->has('search')) {
                $search = request('search');
                $q->where('id', 'ILIKE', "%{$search}%")
                    ->orWhere('num_doc', 'ILIKE', "%{$search}%")
                    ->orWhere('nombre_completo', 'ILIKE', "%{$search}%")
                    ->orWhere('nombres', 'ILIKE', "%{$search}%")
                    ->orWhere('ap_paterno', 'ILIKE', "%{$search}%")
                    ->orWhere('ap_materno', 'ILIKE', "%{$search}%");
            }
        });

        $personas = $personas->paginate(request('page_size'));
        foreach ($personas->items() as &$item) {
            $item->append('user');
            $item->append('tenant');
        };

        return $personas;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonaStoreRequest $request)
    {
        $persona = Persona::create($request->all());
        $persona->validarDatosCompletos();
        return response($persona, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(Persona::find($id));
    }

    /**
     * Display the specified resource.
     */
    public function showByDni(string $num_doc)
    {
        return response(Persona::where('num_doc', $num_doc)->first());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persona $persona)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PersonaUpdateRequest $request, string $id)
    {
        $persona = Persona::find($id);
        $persona->update($request->all());
        $persona->validarDatosCompletos();
        return response($persona);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response(Persona::destroy($id));
    }

    public function importarPersonas(Request $request)
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

                    $estado_civil = null;
                    if ($row['M'] == '1') {
                        $estado_civil = 'Soltero';
                    } else if ($row['M'] == '2') {
                        $estado_civil = 'Casado';
                    } else if ($row['M'] == '3') {
                        $estado_civil = 'Divorciado';
                    } else if ($row['M'] == '4') {
                        $estado_civil = 'Separado';
                    } else if ($row['M'] == '5') {
                        $estado_civil = 'Viudo';
                    }

                    $sexo = null;
                    if ($row['J'] == 1) {
                        $sexo = 'Masculino';
                    } else if ($row['J'] == 2) {
                        $sexo = 'Femenino';
                    }

                    $persona = Persona::where('tipo_doc', $row['A'])->where('num_doc', $row['B'])->first();

                    $fecha_n_excel = $row['I'];
                    $fecha_n = null;
                    if ($fecha_n_excel != null) {
                        $fecha_n = date('Y-m-d', strtotime("1899-12-30 +$fecha_n_excel days"));
                    }

                    if ($persona) {
                        $persona->update([
                            'tipo_doc' => $row['A'],
                            'num_doc' => $row['B'],
                            'nombre_completo' => $row['D'] . ' ' . $row['E'] . ' ' . $row['C'],
                            'nombres' => $row['C'],
                            'ap_paterno' => $row['D'],
                            'ap_materno' => $row['E'],
                            'email' => $row['F'] == null ? $persona->email : $row['F'],
                            'direccion' => $row['G'] == null ? $persona->direccion : $row['G'],
                            'celular' => $row['H'] == null ? $persona->celular : $row['H'],
                            'fec_nacimiento' => $fecha_n ?? $persona->fec_nacimiento,
                            'sexo' => $sexo == null ? $persona->sexo : $sexo,
                            'num_cuenta' => $row['K'] == null ? $persona->num_cuenta : $row['K'],
                            'nacionalidad' => $row['L'] == null ? $persona->nacionalidad : $row['L'],
                            'estado_civil' => $estado_civil == null ? $persona->estado_civil : $estado_civil,
                            'ruc' => $row['N'] == null ? $persona->ruc : $row['N'],
                            'pais' => $row['O'] == null ? $persona->pais : $row['O'],
                            'ubigeo_actual' => $row['P'] == null ? $persona->ubigeo_actual : $row['P'],
                            'ubigeo_nacimiento' => $row['Q'] == null ? $persona->ubigeo_nacimiento : $row['Q']
                        ]);
                    } else {
                        $persona = Persona::create([
                            'tipo_doc' => $row['A'],
                            'num_doc' => $row['B'],
                            'nombre_completo' => $row['D'] . ' ' . $row['E'] . ' ' . $row['C'],
                            'nombres' => $row['C'],
                            'ap_paterno' => $row['D'],
                            'ap_materno' => $row['E'],
                            'email' => $row['F'],
                            'direccion' => $row['G'],
                            'celular' => $row['H'],
                            'fec_nacimiento' => $fecha_n ?? null,
                            'sexo' => $row['J'] == '1' ? 'Masculino' : 'Femenino',
                            'num_cuenta' => $row['K'],
                            'nacionalidad' => $row['L'],
                            'estado_civil' => $estado_civil,
                            'ruc' => $row['N'],
                            'pais' => $row['O'],
                            'ubigeo_actual' => $row['P'],
                            'ubigeo_nacimiento' => $row['Q']
                        ]);
                    }

                    $persona->validarDatosCompletos();
                }
            }
            return $data;
        }
    }

    public function searchByApiDni(string $num_doc)
    {
        try {
            $token = 'apis-token-7902.gyGH2s8poJcci7fAM42bcPV0ro2PEPgj';
            $token2 = 'apis-token-8306.DDeQIBTwNsn4svbi8fN7womB4klfIhHc';
            $responseGratis = Http::withHeaders([
                'Referer' => 'https://apis.net.pe/consulta-dni-api',
                'Authorization' => 'Bearer ' . $token2,
            ])->timeout(7)->get('https://api.apis.net.pe/v2/reniec/dni', [
                'numero' => $num_doc,
            ]);

            if ($responseGratis->successful() && isset($responseGratis['nombres'])) {
                return [
                    'fuente' => 'APIS.NET.PE',
                    'data' => $responseGratis->json()
                ];
            }
        } catch (\Exception $e) {
            //
        }

        return [
            'error' => true,
            'mensaje' => 'No se pudo obtener datos del DNI',
        ];
    }

    public function batch(Request $request)
    {
        $dnis = $request->input('dnis', []);

        if (empty($dnis)) {
            return response()->json([]);
        }

        // obtener todas las personas de una sola vez
        $items = Persona::whereIn('num_doc', $dnis)->get();

        // transformar en objeto indexado por DNI
        $result = [];
        foreach ($items as $item) {
            $result[$item->num_doc] = [
                'id' => $item->id,
                'tipo_doc' => $item->tipo_doc,
                'num_doc' => $item->num_doc,
                'nombre_completo' => $item->nombre_completo,
                'nombres' => $item->nombres,
                'ap_paterno' => $item->ap_paterno,
                'ap_materno' => $item->ap_materno,
                'email' => $item->email,
                'direccion' => $item->direccion,
                'celular' => $item->celular,
                'fec_nacimiento' => $item->fec_nacimiento,
                'sexo' => $item->sexo,
                'num_cuenta' => $item->num_cuenta,
                'nacionalidad' => $item->nacionalidad,
                'estado_civil' => $item->estado_civil,
                'ruc' => $item->ruc,
                'pais' => $item->pais,
                'datos_completos' => $item->datos_completos,
                'ubigeo_actual' => $item->ubigeo_actual,
                'ubigeo_nacimiento' => $item->ubigeo_nacimiento,
                'tenant_id' => $item->tenant_id,
            ];
        }

        return response()->json($result);
    }
}
