<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TenantRequest;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
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
        $tenants = Tenant::orderBy(request('sort_by'), request('direction'));
        $tenants->where(function ($q) {
            if (request()->has('search') && request('search') != '') {
                $q->where('id', 'like', '%' . request('search') . '%')
                    ->orWhere('nombre', 'like', '%' . request('search') . '%');
            }
        });
        $tenants = $tenants->paginate(request('page_size'));

        return response($tenants);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TenantRequest $request)
    {
        return response(Tenant::create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response(Tenant::find($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TenantRequest $request, string $id)
    {
        return response(Tenant::find($id)->update($request->all()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response(Tenant::destroy($id));
    }

    public function changeAccessUserTenant(Request $request)
    {
        $request->validate([
            'id_user_tenant' => 'required|integer|exists:user_tenant,id',
            'active' => 'required|boolean',
        ]);

        DB::table('user_tenant')
            ->where('id', $request->id_user_tenant)
            ->update(['active' => $request->active]);
    }
}
