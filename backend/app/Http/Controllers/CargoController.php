<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CargoRequest;
use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
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
        $cargos = Cargo::orderBy(request('sort_by'), request('direction'));
        $cargos->where(function ($q) {
            if (request()->has('search')) {
                $search = request('search');
                $q->where('id', 'ILIKE', "%{$search}%")
                    ->orWhere('codigo', 'ILIKE', "%{$search}%")
                    ->orWhere('nombre', 'ILIKE', "%{$search}%");
            }
        });
        $cargos = $cargos->paginate(request('page_size'));
        foreach ($cargos->items() as &$item) {
            $item->append('tenant');
        }
        return response($cargos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CargoRequest $request)
    {
        return response(Cargo::create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(Cargo::find($id));
    }

    /**
     * Display the specified resource.
     */
    public function showByCodigo(string $codigo)
    {
        return response(Cargo::where('codigo', $codigo)->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CargoRequest $request, string $id)
    {
        return response(Cargo::find($id)->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response(Cargo::destroy($id));
    }

    public function batch(Request $request)
    {
        $codigos = $request->input('codigos', []);

        if (empty($codigos)) {
            return response()->json([]);
        }

        $items = Cargo::whereIn('codigo', $codigos)->get();

        $result = [];
        foreach ($items as $item) {
            $result[$item->codigo] = [
                'id' => $item->id,
                'codigo' => $item->codigo,
                'nombre' => $item->nombre,
                'ordinal'  => $item->ordinal,
                'tenant_id'  => $item->tenant_id,
            ];
        }

        return response()->json($result);
    }
}
