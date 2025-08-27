<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sistema\SistemaStoreRequest;
use App\Http\Requests\Sistema\SistemaUpdateRequest;
use App\Models\Sistema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SistemaController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('America/Lima');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sistemas = Sistema::orderBy(request('sort_by'), request('direction'));
        $sistemas->where(function ($q) {
            if (request()->has('search') && request('search') != '') {
                $q->where('id', 'like', '%' . request('search') . '%')
                    ->orWhere('nombre', 'like', '%' . request('search') . '%');
            }
        });
        $sistemas = $sistemas->paginate(request('page_size'));

        return response($sistemas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SistemaStoreRequest $request)
    {
        return response(Sistema::create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(Sistema::find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SistemaUpdateRequest $request, string $id)
    {
        return response(Sistema::find($id)->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response(Sistema::destroy($id));
    }

    public function changeAccessUserSistema(Request $request)
    {
        $request->validate([
            'id_user_sistema' => 'required|integer|exists:user_sistema,id',
            'active' => 'required|boolean',
        ]);
        
        DB::table('user_sistema')
            ->where('id', $request->id_user_sistema)
            ->update(['active' => $request->active]);
    }
}
