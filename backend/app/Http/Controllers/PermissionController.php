<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
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
        $permisos = Permission::orderBy(request('sort_by'), request('direction'));
        $permisos->where(function ($q) {
            if (request()->has('search') && request('search') != '') {
                $q->where('id', 'like', '%' . request('search') . '%')
                    ->orWhere('name', 'like', '%' . request('search') . '%')
                    ->orWhere('label', 'like', '%' . request('search') . '%')
                    ->orWhere('module', 'like', '%' . request('search') . '%');
            }
        });
        $permisos = $permisos->paginate(request('page_size'));
        foreach ($permisos->items() as &$item) {
            $item->append('sistema');
        }
        return response($permisos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response(Permission::create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(Permission::find($id)->append('sistema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response(Permission::find($id)->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response(Permission::destroy($id));
    }
}
