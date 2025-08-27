<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Proyectos\ProyectoStoreRequest;
use App\Http\Requests\Proyectos\ProyectoUpdateRequest;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
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
        $proyectos = Proyecto::leftJoin('oficinas', 'proyectos.oficina_id', '=', 'oficinas.id')
            ->select('proyectos.*', 'oficinas.nombre AS nombre_oficina')
            ->orderBy(request('sort_by'), request('direction'));
        $proyectos->where(function ($q) {
            if (request()->has('estado') && request('estado') != '') {
                $q->where('proyectos.estado', request('estado'));
            };
        })->where(function ($q) {
            $q->where('proyectos.id', 'like', '%' . request('search') . '%')
                ->orWhere('proyectos.tipo', 'like', '%' . request('search') . '%')
                ->orWhere('proyectos.meta', 'like', '%' . request('search') . '%')
                ->orWhere('proyectos.codigo', 'like', '%' . request('search') . '%')
                ->orWhere('proyectos.nombre', 'like', '%' . request('search') . '%')
                ->orWhere('proyectos.estado', 'like', '%' . request('search') . '%')
                ->orWhere('oficinas.nombre', 'like', '%' . request('search') . '%');
        });

        $proyectos = $proyectos->paginate(request('page_size'));
        foreach ($proyectos->items() as &$item) {
            $item->append('oficina');
            $item->append('tenant');
        }
        return response($proyectos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProyectoStoreRequest $request)
    {
        $proyecto = Proyecto::create($request->all());
        return response($proyecto);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(Proyecto::find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProyectoUpdateRequest $request, string $id)
    {
        $proyecto = Proyecto::find($id);
        $proyecto->update($request->all());
        return response($proyecto, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response(Proyecto::destroy($id));
    }

    public function contarPersonal()
    {
        //
    }

    public function batch(Request $request)
    {
        $codigos = $request->input('codigos', []);

        if (empty($codigos)) {
            return response()->json([]);
        }

        $items = Proyecto::whereIn('codigo', $codigos)->get();

        $result = [];
        foreach ($items as $item) {
            $result[$item->codigo] = [
                'id'                   => $item->id,
                'num_doc_resolucion'   => $item->num_doc_resolucion,
                'tipo'                 => $item->tipo,
                'meta'                 => $item->meta,
                'meta_codigo'          => $item->meta_codigo,
                'codigo'               => $item->codigo,
                'cui'                  => $item->cui,
                'nombre'               => $item->nombre,
                'obra'                 => $item->obra,

                'pliego'               => $item->pliego,
                'programa_presupuestal' => $item->programa_presupuestal,
                'modalidad_ejecucion'  => $item->modalidad_ejecucion,
                'fte_fto'              => $item->fte_fto,

                'estado'               => $item->estado,
                'cant_personal'        => $item->cant_personal,

                'escala'               => $item->escala,
                'tipo_categoria'       => $item->tipo_categoria,
                'tipo_acceso'          => $item->tipo_acceso,

                'charaux1'             => $item->charaux1,
                'charaux2'             => $item->charaux2,
                'charaux3'             => $item->charaux3,
                'charaux4'             => $item->charaux4,
                'charaux5'             => $item->charaux5,
                'charaux6'             => $item->charaux6,
                'charaux7'             => $item->charaux7,
                'charaux8'             => $item->charaux8,
                'charaux9'             => $item->charaux9,
                'charaux10'            => $item->charaux10,

                'oficina_id'           => $item->oficina_id,
                'ubigeo_codigo'        => $item->ubigeo_codigo,
                'tenant_id'            => $item->tenant_id,
            ];
        }

        return response()->json($result);
    }
}
