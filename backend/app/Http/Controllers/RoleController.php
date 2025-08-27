<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\Role;

class RoleController extends Controller
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
        $roles = Role::orderBy(request('sort_by'), request('direction'));

        $roles->where(function ($q) {
            if (request()->has('sistema_id') && request('sistema_id') != '') {
                $q->where('sistema_id', request('sistema_id'));
            };
        })->where(function ($q) {
            if (request()->has('search') && request('search') != '') {
                $q->where('id', 'like', '%' . request('search') . '%')
                    ->orWhere('name', 'like', '%' . request('search') . '%');
            }
        });
        $roles = $roles->paginate(request('page_size'));
        foreach ($roles->items() as &$item) {
            $item->append('sistema');
            $item->append('tenant');
        }
        return response($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        return response(Role::create([
            'name' => $request->name,
            'sistema_id' => $request->sistema_id,
            'guard_name' => 'api',
            'tenant_id' => $request->tenant_id,
        ]), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id)->append('sistema')->append('tenant');
        $modules = Permission::select('module')->where('sistema_id', $role->sistema_id)->distinct()->get();
        $permisos = Permission::select('name', 'label', 'module')->where('sistema_id', $role->sistema_id)->orderBy('id', 'asc')->get();
        $permisosSelected = $role->getPermissionNames();
        return response([
            'role' => $role,
            'modules' => $modules,
            'permisos' => $permisos,
            'permisosSelected' => $permisosSelected
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        $role = Role::find($id);
        $role->update([
            'name' => $request->name,
            'sistema_id' => $request->sistema_id,
            'guard_name' => 'api',
            'tenant_id' => $request->tenant_id,
        ]);
        $role->syncPermissions($request->permisosSelected);
        return response($role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response(Role::destroy($id));
    }
}
