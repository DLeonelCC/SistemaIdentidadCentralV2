<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SedeRequest;
use App\Models\Sede;
use Illuminate\Http\Request;

class SedeController extends Controller
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
        $sedes = Sede::orderBy(request('sort_by'), request('direction'));
        $sedes->where(function ($q) {
            if (request()->has('search')) {
                $search = request('search');
                $q->where('id', 'ILIKE', "%{$search}%")
                    ->orWhere('nombre', 'ILIKE', "%{$search}%")
                    ->orWhere('direccion', 'ILIKE', "%{$search}%")
                    ->orWhere('telefono', 'ILIKE', "%{$search}%")
                    ->orWhere('celular', 'ILIKE', "%{$search}%");
            }
        });
        $sedes = $sedes->paginate(request('page_size'));
        foreach ($sedes->items() as &$item) {
            $item->append('tenant');
        }
        return response($sedes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SedeRequest $request)
    {
        return response(Sede::create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(Sede::find($id));
    }

    /**
     * Display the specified resource.
     */
    public function showByCodigo(string $codigo)
    {
        return response(Sede::where('codigo', $codigo)->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SedeRequest $request, string $id)
    {
        return response(Sede::find($id)->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response(Sede::destroy($id));
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

        $items = Sede::whereIn('codigo', $codigos)->get();

        $result = [];
        foreach ($items as $item) {
            $result[$item->codigo] = [
                'id' => $item->id,
                'codigo' => $item->codigo,
                'nombre' => $item->nombre,
                'direccion' => $item->codigo,
                'telefono' => $item->codigo,
                'celular' => $item->codigo,
                'cant_personal' => $item->codigo,
                'tenant_id' => $item->codigo,
            ];
        }

        return response()->json($result);
    }
}
