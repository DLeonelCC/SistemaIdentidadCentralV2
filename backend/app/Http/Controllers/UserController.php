<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Models\Persona;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
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
        $users = User::leftJoin('personas', 'users.persona_num_doc', '=', 'personas.num_doc')
            ->select(
                'users.*',
                'personas.id AS persona_id',
                'personas.tipo_doc AS tipo_doc_persona',
                'personas.num_doc AS num_doc_persona',
                'personas.nombre_completo AS nombre_completo'
            );
        $users->where(function ($q) {
            if (request()->has('tipo_doc') && request('tipo_doc') != '') {
                $q->where('personas.tipo_doc', request('tipo_doc'));
            };
        })->where(function ($q) {
            if (request()->has('num_doc') && request('num_doc') != '') {
                $q->where('personas.num_doc', request('num_doc'));
            };
        })->where(function ($q) {
            if (request()->has('tipo') && request('tipo') != '') {
                $q->where('users.tipo', request('tipo'));
            };
        })->where(function ($q) {
            if (request()->has('estado') && request('estado') != '') {
                $q->where('users.estado', request('estado'));
            };
        })->where(function ($q) {
            $q->where('users.id', 'like', '%' . request('search') . '%')
                ->orWhere('users.tipo', 'like', '%' . request('search') . '%')
                ->orWhere('users.name', 'like', '%' . request('search') . '%')
                ->orWhere('users.email', 'like', '%' . request('search') . '%')
                ->orWhere('personas.num_doc', 'like', '%' . request('search') . '%');
        });

        $users = $users->paginate(request('page_size'));
        foreach ($users->items() as &$item) {
            $item->append('persona');
            $item->append('personal');
            $item->append('cargo');
            $item->append('oficina');
            $item->append('tenant');
            $item->rolesSelected = $item->getRoleNames();
        }
        return response($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::select('name')->orderBy('id', 'asc')->get();
        return response($roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'tipo' => $request->tipo,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'estado' => $request->estado,
            'cargo_id' => $request->cargo_id,
            'oficina_id' => $request->oficina_id,
            'persona_num_doc' => $request->persona_num_doc,
            'tenant_id' => $request->tenant_id,
        ]);
        $user->syncRoles($request->rolesSelected);
        return response($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::leftJoin('personas', 'users.persona_num_doc', '=', 'personas.num_doc')
            ->select(
                'users.*',
                'personas.id AS persona_id',
                'personas.tipo_doc AS tipo_doc_persona',
                'personas.num_doc AS num_doc_persona',
            )
            ->where('users.id', $id)
            ->first();

        $user->append('persona');
        $user->append('personal');
        $user->append('cargo');
        $user->append('oficina');
        $user->append('tenant');
        $user->rolesSelected = $user->getRoleNames();

        return response($user);
    }

    public function showByDni(Request $request, string $num_doc)
    {
        $user = User::leftJoin('personas', 'users.persona_num_doc', '=', 'personas.num_doc')
            ->select(
                'users.*',
                'personas.id AS persona_id',
                'personas.tipo_doc AS tipo_doc_persona',
                'personas.num_doc AS num_doc_persona',
            )
            ->where('users.tipo', $request->tipo)
            ->where('users.persona_num_doc', $num_doc)
            ->first();

        if ($user) {
            $user->append('persona');
            $user->append('personal');
            $user->append('cargo');
            $user->append('oficina');
            $user->rolesSelected = $user->getRoleNames();
        }

        return response($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($request->id_user);
        $persona = Persona::find($request->id_persona);
        $personal = Personal::find($request->id_personal);

        $request->validate([
            // User
            'name' => 'required',
            'email_user' => $user
                ? ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)]
                : ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],

            // Personal
            'sis_pen' => 'required',
            'tipo_afp' => 'exclude_unless:sis_pen,AFP|required',
            'tip_com_seg' => 'exclude_unless:sis_pen,AFP|required',

            // Persona
            'tipo_doc' => 'required',
            'num_doc' => $persona
                ? ['max:11', 'string', 'required', Rule::unique('personas', 'num_doc')->ignore($persona->id)]
                : ['max:11', 'string', 'required', Rule::unique('personas', 'num_doc')],

            'nombre_completo' => 'nullable|max:573',
            'nombres' => 'required|max:191',
            'ap_paterno' => 'required|max:191',
            'ap_materno' => 'required|max:191',
            'direccion' => 'nullable|max:191',
            'celular' => 'size:9|nullable',

            'num_cuenta' => $persona
                ? ['nullable', 'string', 'size:11', Rule::unique('personas', 'num_cuenta')->ignore($persona->id)]
                : ['nullable', 'string', 'size:11', Rule::unique('personas', 'num_cuenta')],

            'ruc' => $persona
                ? ['nullable', 'string', 'size:11', Rule::unique('personas', 'ruc')->ignore($persona->id)]
                : ['nullable', 'string', 'size:11', Rule::unique('personas', 'ruc')],
        ]);

        $persona = Persona::updateOrCreate(
            ['num_doc' => $request->num_doc],
            [
                'tipo_doc' => $request->tipo_doc,
                'num_doc' => $request->num_doc,
                'nombre_completo' => $request->nombre_completo,
                'nombres' => $request->nombres,
                'ap_paterno' => $request->ap_paterno,
                'ap_materno' => $request->ap_materno,
                'email' => $request->email_persona,
                'direccion' => $request->direccion,
                'celular' => $request->celular,
                'fec_nacimiento' => $request->fec_nacimiento,
                'sexo' => $request->sexo,
                'num_cuenta' => $request->num_cuenta,
                'nacionalidad' => $request->nacionalidad,
                'estado_civil' => $request->estado_civil,
                'ruc' => $request->ruc,
                'pais' => $request->pais,
                'ubigeo_actual' => $request->ubigeo_actual,
                'ubigeo_nacimiento' => $request->ubigeo_nacimiento,
            ]
        );

        $personal = Personal::updateOrCreate(
            ['persona_num_doc' => $request->num_doc],
            [
                'codigo' => $request->codigo,
                'codigo_air' => $request->codigo_air,
                'fec_ini' => $request->fec_ini,
                'fec_fin' => $request->fec_fin,
                'tipo_contrato' => $request->tipo_contrato,
                'categoria_ocupacional' => $request->categoria_ocupacional,
                'sis_pen' => $request->sis_pen,
                'tipo_afp' => $request->tipo_afp,
                'cuspp' => $request->cuspp,
                'tip_com_seg' => $request->tip_com_seg,
                'observacion' => $request->observacion,
                'meta_id' => $request->meta_id,
                'persona_num_doc' => $request->num_doc,
            ]
        );

        if ($user) {
            $user->update([
                'name' => $request->name,
                'tipo' => $request->tipo,
                'email' => $request->email_user,
                'password' => $request->password == null ? $user->password : Hash::make($request->password),
                'estado' => $request->estado,
                'cargo_id' => $request->cargo_id,
                'oficina_id' => $request->oficina_id,
                'persona_num_doc' => $request->num_doc,
                'tenant_id' => $request->tenant_id,
            ]);
        } else {
            $user = User::updateOrCreate(
                ['email' => $request->email, 'persona_num_doc' => $request->num_doc],
                [
                    'name' => $request->name,
                    'tipo' => $request->tipo,
                    'email' => $request->email_user,
                    'password' => $request->password == null && $user ? $user->password : Hash::make($request->password),
                    'estado' => $request->estado,
                    'cargo_id' => $request->cargo_id,
                    'oficina_id' => $request->oficina_id,
                    'persona_num_doc' => $request->num_doc,
                    'tenant_id' => $request->tenant_id,
                ]
            );
        }

        $user->persona = $persona;
        $user->personal = $personal;
        return response($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response(User::destroy($id));
    }

    public function getRoles(Request $request)
    {
        $user_rol = DB::table('model_has_roles')
            ->leftJoin('roles', 'model_has_roles.role_id', 'roles.id')
            ->select('roles.*', 'model_has_roles.model_id AS user_id');
        $user_rol->where(function ($q) {
            $q->where('model_type', 'App\Models\core_central\User')
                ->orWhere('model_type', '=', 'App\\Models\\core_central\\User');
        })->where(function ($q) {
            if (request()->has('user_id') && request('user_id') != '') {
                $q->where('model_has_roles.model_id', request('user_id'));
            };
        })->where(function ($q) {
            if (request()->has('sistema_id') && request('sistema_id') != '') {
                $q->where('roles.sistema_id', request('sistema_id'));
            };
        });

        $user_rol = $user_rol->paginate(request('page_size'));
        foreach ($user_rol->items() as &$item) {
            $item->permisos = DB::table('role_has_permissions')
                ->leftJoin('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->select('permissions.label')
                ->where('role_has_permissions.role_id', $item->id)
                ->get();
        }

        return $user_rol;
    }

    public function addRole(Request $request)
    {
        DB::table('model_has_roles')->insert([
            'model_type' => 'App\Models\core_central\User',
            'model_id' => $request->user_id,
            'role_id' => $request->role_id,
        ]);
    }

    public function deleteRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'role_id' => 'required|integer',
        ]);

        DB::table('model_has_roles')
            ->where('role_id', $request->role_id)
            ->where(function ($q) {
                $q->where('model_type', '=', 'App\Models\User')
                    ->orWhere('model_type', '=', 'App\Models\core_central\User');
            })
            ->where('model_id', $request->user_id)
            ->delete();
    }

    public function getTenants(Request $request)
    {
        $user_tenant = DB::table('user_tenant')
            ->leftJoin('tenants', 'user_tenant.tenant_id', 'tenants.id')
            ->select('tenants.*', 'user_tenant.id AS id_user_tenant', 'user_tenant.user_id AS user_id', 'user_tenant.active AS active');
        $user_tenant->where(function ($q) {
            if (request()->has('user_id') && request('user_id') != '') {
                $q->where('user_tenant.user_id', request('user_id'));
            };
        });

        $user_tenant = $user_tenant->paginate(request('page_size'));

        return $user_tenant;
    }

    public function addTenant(Request $request)
    {
        DB::table('user_tenant')->insert([
            'user_id' => $request->user_id,
            'tenant_id' => $request->tenant_id,
        ]);
    }

    public function deleteTenant(string $id)
    {
        DB::table('user_tenant')->where('id', $id)->delete();
    }

    public function getSistemas(Request $request)
    {
        $user_sistema = DB::table('user_sistema')
            ->leftJoin('sistemas', 'user_sistema.sistema_id', 'sistemas.id')
            ->select('sistemas.*', 'user_sistema.id AS id_user_sistema', 'user_sistema.user_id AS user_id', 'user_sistema.active AS active');
        $user_sistema->where(function ($q) {
            if (request()->has('user_id') && request('user_id') != '') {
                $q->where('user_sistema.user_id', request('user_id'));
            };
        });

        $user_sistema = $user_sistema->paginate(request('page_size'));
        foreach ($user_sistema->items() as &$item) {
            $item->roles = DB::table('model_has_roles')
                ->leftJoin('roles', 'model_has_roles.role_id', 'roles.id')
                ->select('roles.name')
                ->where('roles.sistema_id', $item->id)
                ->where(function ($q) {
                    $q->where('model_type', 'App\Models\core_central\User')
                        ->orWhere('model_type', '=', 'App\\Models\\core_central\\User');
                })
                ->where('model_has_roles.model_id', $item->user_id)
                ->get();
        }

        return $user_sistema;
    }

    public function addSistema(Request $request)
    {
        DB::table('user_sistema')->insert([
            'user_id' => $request->user_id,
            'sistema_id' => $request->sistema_id,
        ]);
    }

    public function deleteSistema(string $id)
    {
        DB::table('user_sistema')->where('id', $id)->delete();
    }
}
