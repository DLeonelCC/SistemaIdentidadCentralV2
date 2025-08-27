<?php

namespace App\Http\Controllers;

use App\Http\Requests\archivos\ArchivoStoreRequest;
use App\Http\Requests\archivos\ArchivoUpdateRequest;
use App\Models\Archivo;
use Illuminate\Http\Request;

class ArchivoController extends Controller
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
        $archivos = Archivo::orderBy(request('sort_by'), request('direction'));
        $archivos->where(function ($q) {
            if (request()->has('tipo') && request('tipo') != '') {
                $q->where('archivos.tipo', request('tipo'));
            };
        })->where(function ($q) {
            $q->where('id', 'like', '%' . request('search') . '%')
                ->orWhere('tipo', 'like', '%' . request('search') . '%')
                ->orWhere('tipo_doc', 'like', '%' . request('search') . '%');
        });

        $archivos = $archivos->paginate(request('page_size'));
        foreach ($archivos->items() as &$item) {
            $item->append('persona');
            $item->append('tenant');
        }
        return response($archivos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArchivoStoreRequest $request)
    {
        $archivo = Archivo::create([
            'tipo' => $request->tipo,
            'tipo_doc' => $request->tipo_doc,
            'num_doc' => $request->num_doc,
            'fecha' => $request->fecha,
            'descripcion' => $request->descripcion,
            'contrato_id' => $request->contrato_id,
        ]);
        $archivo->saveArchivo($request->file('file'));
        return response($archivo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(Archivo::find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArchivoUpdateRequest $request)
    {
        $archivo = Archivo::find($request->id);
        $archivo->update([
            'tipo' => $request->tipo,
            'tipo_doc' => $request->tipo_doc,
            'num_doc' => $request->num_doc,
            'fecha' => $request->fecha,
            'descripcion' => $request->descripcion,
            'contrato_id' => $request->contrato_id,
        ]);
        if ($request->hasFile('file')) {
            $archivo->deleteArchivo();
            $archivo->saveArchivo($request->file('file'));
        }
        return response($archivo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $archivo = Archivo::find($id);
        $archivo->deleteArchivo();
        $archivo->destroy($id);
        return response($archivo);
    }
}
