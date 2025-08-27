<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OficinaRequest;
use App\Models\Oficina;
use Illuminate\Http\Request;

class OficinaController extends Controller
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
        $oficinas = Oficina::orderBy(request('sort_by'), request('direction'));
        $oficinas->where(function ($q) {
            if (request()->has('search')) {
                $search = request('search');
                $q->where('id', 'ILIKE', "%{$search}%")
                    ->orWhere('codigo', 'ILIKE', "%{$search}%")
                    ->orWhere('siglas', 'ILIKE', "%{$search}%")
                    ->orWhere('nombre', 'ILIKE', "%{$search}%");
            }
        });
        $oficinas = $oficinas->paginate(request('page_size'));
        foreach ($oficinas->items() as &$item) {
            $item->append('tenant');
        }
        return response($oficinas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OficinaRequest $request)
    {
        return response(Oficina::create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(Oficina::find($id));
    }

    /**
     * Display the specified resource.
     */
    public function showByCodigo(string $codigo)
    {
        return response(Oficina::where('codigo', $codigo)->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OficinaRequest $request, string $id)
    {
        return response(Oficina::find($id)->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response(Oficina::destroy($id));
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

        $items = Oficina::whereIn('codigo', $codigos)->get();

        $result = [];
        foreach ($items as $item) {
            $result[$item->codigo] = [
                'id' => $item->id,
                'codigo' => $item->codigo,
                'siglas' => $item->siglas,
                'nombre' => $item->nombre,
                'ordinal' => $item->ordinal,
                'cant_personal' => $item->cant_personal,
                'tenant_id' => $item->tenant_id,
            ];
        }

        return response()->json($result);
    }
}
