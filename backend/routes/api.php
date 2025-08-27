<?php

use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\OficinaController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SedeController;
use App\Http\Controllers\SistemaController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UbigeoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Rutas públicas - Consultas
Route::get('consultas-empresa', [EmpresaController::class, 'getEmpresa']);

//Ubigeo
Route::get('ubigeo', [UbigeoController::class, 'getUbigeo']);
Route::get('departamentos', [UbigeoController::class, 'getDepartamentos']);
Route::get('provincias', [UbigeoController::class, 'getProvincias']);
Route::get('distritos', [UbigeoController::class, 'getDistritos']);

//Rutas protegidas 
Route::middleware('auth:api')->group(function () {

    //Profile
    Route::get('get-my-information', [ProfileController::class, 'getMyInformation']);
    Route::post('update-profile-information', [ProfileController::class, 'updateProfileInformation']);
    Route::post('update-password', [ProfileController::class, 'updatePassword']);
    Route::get('delete-profile-photo', [ProfileController::class, 'deleteProfilePhoto']);

    //Dashboard
    Route::get('dashboard', [EmpresaController::class, 'getDashboard']);

    //Sistemas
    Route::get('sistemas', [SistemaController::class, 'index']);
    Route::get('sistemas/{sitema}', [SistemaController::class, 'show']);
    Route::post('sistemas-change-access-user-sistema', [SistemaController::class, 'changeAccessUserSistema']);
    Route::group(['middleware' => ['custom_can:create_sistemas']], function () {
        Route::post('sistemas', [SistemaController::class, 'store']);
    });
    Route::group(['middleware' => ['custom_can:edit_sistemas']], function () {
        Route::put('sistemas/{sistema}', [SistemaController::class, 'update']);
    });
    Route::group(['middleware' => ['custom_can:delete_tenants']], function () {
        Route::delete('sistemas/{sitema}', [SistemaController::class, 'destroy']);
    });

    //Tenants
    Route::get('tenants', [TenantController::class, 'index']);
    Route::get('tenants/{tenant}', [TenantController::class, 'show']);
    Route::post('tenants-change-access-user-tenant', [TenantController::class, 'changeAccessUserTenant']);
    Route::group(['middleware' => ['custom_can:create_tenants']], function () {
        Route::post('tenants', [TenantController::class, 'store']);
    });
    Route::group(['middleware' => ['custom_can:edit_tenants']], function () {
        Route::put('tenants/{tenant}', [TenantController::class, 'update']);
    });
    Route::group(['middleware' => ['custom_can:delete_tenants']], function () {
        Route::delete('tenants/{tenant}', [TenantController::class, 'destroy']);
    });

    //Cargos
    Route::get('cargos', [CargoController::class, 'index']);
    Route::get('cargos/{cargo}', [CargoController::class, 'show']);
    Route::group(['middleware' => ['custom_can:create_cargos']], function () {
        Route::post('cargos', [CargoController::class, 'store']);
    });
    Route::group(['middleware' => ['custom_can:edit_cargos']], function () {
        Route::put('cargos/{cargo}', [CargoController::class, 'update']);
    });
    Route::group(['middleware' => ['custom_can:delete_cargos']], function () {
        Route::delete('cargos/{cargo}', [CargoController::class, 'destroy']);
    });

    //Personals
    Route::get('personals', [PersonalController::class, 'index']);
    Route::get('personals/{personal}', [PersonalController::class, 'show']);
    Route::get('personals/personal-by-dni/{personal}', [PersonalController::class, 'showByDni']);
    Route::group(['middleware' => ['custom_can:create_personals']], function () {
        Route::post('personals', [PersonalController::class, 'store']);
        Route::post('personals-importar-personal', [PersonalController::class, 'importarPersonal']);
    });
    Route::group(['middleware' => ['custom_can:edit_personals']], function () {
        Route::put('personals/{personal}', [PersonalController::class, 'update']);
    });
    Route::group(['middleware' => ['custom_can:delete_personals']], function () {
        Route::delete('personals/{personal}', [PersonalController::class, 'destroy']);
    });

    //Personas
    Route::get('personas', [PersonaController::class, 'index']);
    Route::get('personas/{persona}', [PersonaController::class, 'show']);
    Route::get('personas/persona-by-dni/{persona}', [PersonaController::class, 'showByDni']);
    Route::get('personas/persona-by-api-dni/{persona}', [PersonaController::class, 'searchByApiDni']);
    Route::group(['middleware' => ['custom_can:create_personas']], function () {
        Route::post('personas', [PersonaController::class, 'store']);
        Route::post('personas-importar-personas', [PersonaController::class, 'importarPersonas']);
    });
    Route::group(['middleware' => ['custom_can:edit_personas']], function () {
        Route::put('personas/{persona}', [PersonaController::class, 'update']);
    });
    Route::group(['middleware' => ['custom_can:delete_personas']], function () {
        Route::delete('personas/{persona}', [PersonaController::class, 'destroy']);
    });

    //Users
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/create', [UserController::class, 'create']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::get('users/user-by-dni/{user}', [UserController::class, 'showByDni']);
    Route::group(['middleware' => ['custom_can:create_users']], function () {
        Route::post('users', [UserController::class, 'store']);
    });
    Route::group(['middleware' => ['custom_can:edit_users']], function () {
        Route::put('users/{user}', [UserController::class, 'update']);
    });
    Route::group(['middleware' => ['custom_can:delete_users']], function () {
        Route::delete('users/{user}', [UserController::class, 'destroy']);
    });

    Route::get('users-roles', [UserController::class, 'getRoles']);
    Route::post('users-roles-add', [UserController::class, 'addRole']);
    Route::post('users-roles-delete', [UserController::class, 'deleteRole']);

    Route::get('users-tenants', [UserController::class, 'getTenants']);
    Route::post('users-tenants-add', [UserController::class, 'addTenant']);
    Route::delete('users-tenants-delete/{user_tenant}', [UserController::class, 'deleteTenant']);

    Route::get('users-sistemas', [UserController::class, 'getSistemas']);
    Route::post('users-sistemas-add', [UserController::class, 'addSistema']);
    Route::delete('users-sistemas-delete/{user_sistema}', [UserController::class, 'deleteSistema']);

    Route::get('users-proyectos', [UserController::class, 'getProyectos']);
    Route::post('users-proyectos-add', [UserController::class, 'addProyecto']);
    Route::delete('users-proyectos-delete/{user_proyecto}', [UserController::class, 'deleteProyecto']);

    //Roles
    Route::get('roles', [RoleController::class, 'index']);
    Route::get('roles/{role}', [RoleController::class, 'show']);
    Route::group(['middleware' => ['custom_can:create_roles']], function () {
        Route::post('roles', [RoleController::class, 'store']);
    });
    Route::group(['middleware' => ['custom_can:edit_roles']], function () {
        Route::put('roles/{role}', [RoleController::class, 'update']);
    });
    Route::group(['middleware' => ['custom_can:delete_roles']], function () {
        Route::delete('roles/{role}', [RoleController::class, 'destroy']);
    });

    //Permisos
    Route::get('permisos', [PermissionController::class, 'index']);
    Route::get('permisos/{role}', [PermissionController::class, 'show']);
    Route::group(['middleware' => ['custom_can:create_permisos']], function () {
        Route::post('permisos', [PermissionController::class, 'store']);
    });
    Route::group(['middleware' => ['custom_can:edit_permisos']], function () {
        Route::put('permisos/{role}', [PermissionController::class, 'update']);
    });
    Route::group(['middleware' => ['custom_can:delete_permisos']], function () {
        Route::delete('permisos/{role}', [PermissionController::class, 'destroy']);
    });

    //Empresa
    Route::get('get-empresa-information', [EmpresaController::class, 'getEmpresa']);
    Route::group(['middleware' => ['custom_can:edit_empresa']], function () {
        Route::post('update-empresa-information', [EmpresaController::class, 'updateEmpresaInformation']);
        Route::post('update-empresa-logo', [EmpresaController::class, 'updateEmpresaLogo']);
        Route::get('delete-empresa-logo', [EmpresaController::class, 'deleteEmpresaLogo']);
    });

    //Sedes
    Route::get('sedes', [SedeController::class, 'index']);
    Route::get('sedes/{sede}', [SedeController::class, 'show']);
    Route::get('sedes-contar-contratos', [SedeController::class, 'contarPersonal']);
    Route::group(['middleware' => ['custom_can:create_sedes']], function () {
        Route::post('sedes', [SedeController::class, 'store']);
    });
    Route::group(['middleware' => ['custom_can:edit_sedes']], function () {
        Route::put('sedes/{sede}', [SedeController::class, 'update']);
    });
    Route::group(['middleware' => ['custom_can:delete_sedes']], function () {
        Route::delete('sedes/{sede}', [SedeController::class, 'destroy']);
    });

    //Oficinas
    Route::get('oficinas', [OficinaController::class, 'index']);
    Route::get('oficinas/{oficina}', [OficinaController::class, 'show']);
    Route::get('oficinas-contar-personal', [OficinaController::class, 'contarPersonal']);
    Route::group(['middleware' => ['custom_can:create_oficinas']], function () {
        Route::post('oficinas', [OficinaController::class, 'store']);
    });
    Route::group(['middleware' => ['custom_can:edit_oficinas']], function () {
        Route::put('oficinas/{oficina}', [OficinaController::class, 'update']);
    });
    Route::group(['middleware' => ['custom_can:delete_oficinas']], function () {
        Route::delete('oficinas/{oficina}', [OficinaController::class, 'destroy']);
    });

    //Proyectos
    Route::get('proyectos', [ProyectoController::class, 'index']);
    Route::get('proyectos/{proyecto}', [ProyectoController::class, 'show']);
    Route::get('proyectos-contar-personal', [ProyectoController::class, 'contarPersonal']);
    Route::group(['middleware' => ['custom_can:create_proyectos']], function () {
        Route::post('proyectos', [ProyectoController::class, 'store']);
    });
    Route::group(['middleware' => ['custom_can:edit_proyectos']], function () {
        Route::put('proyectos/{proyecto}', [ProyectoController::class, 'update']);
    });
    Route::group(['middleware' => ['custom_can:delete_proyectos']], function () {
        Route::delete('proyectos/{proyecto}', [ProyectoController::class, 'destroy']);
    });

    //Archivos
    Route::get('archivos', [ArchivoController::class, 'index']);
    Route::get('archivos/{archivo}', [ArchivoController::class, 'show']);
    // Route::group(['middleware' => ['custom_can:create_archivos']], function () {
    Route::post('archivos', [ArchivoController::class, 'store']);
    // });
    Route::group(['middleware' => ['custom_can:edit_archivos']], function () {
        Route::post('archivos-update', [ArchivoController::class, 'update']);
    });
    Route::group(['middleware' => ['custom_can:delete_archivos']], function () {
        Route::delete('archivos/{archivo}', [ArchivoController::class, 'destroy']);
    });
});

Route::prefix('client')->middleware(['client', 'throttle:1000,1'])->group(function () { // aumentando de 60 a 1000 consultas por minuto

    //Profile
    Route::get('get-my-information', [ProfileController::class, 'getMyInformation']);
    Route::post('update-profile-information', [ProfileController::class, 'updateProfileInformation']);
    Route::post('update-password', [ProfileController::class, 'updatePassword']);
    Route::get('delete-profile-photo', [ProfileController::class, 'deleteProfilePhoto']);

    // Cargos Client
    Route::get('cargos', [CargoController::class, 'index']);
    Route::get('cargos/{cargo}', [CargoController::class, 'show']);
    Route::get('cargos/cargo-by-codigo/{cargo}', [CargoController::class, 'showByCodigo']);
    Route::post('cargos', [CargoController::class, 'store']);
    Route::put('cargos/{cargo}', [CargoController::class, 'update']);
    Route::delete('cargos/{cargo}', [CargoController::class, 'destroy']);
    Route::post('/cargos/batch', [CargoController::class, 'batch']);

    // Personas Client
    Route::get('personas', [PersonaController::class, 'index']);
    Route::get('personas/{persona}', [PersonaController::class, 'show']);
    Route::get('personas/persona-by-dni/{persona}', [PersonaController::class, 'showByDni']);
    Route::get('personas/persona-by-api-dni/{persona}', [PersonaController::class, 'searchByApiDni']);
    Route::post('personas', [PersonaController::class, 'store']);
    Route::post('personas-importar-personas', [PersonaController::class, 'importarPersonas']);
    Route::put('personas/{persona}', [PersonaController::class, 'update']);
    Route::delete('personas/{persona}', [PersonaController::class, 'destroy']);
    Route::post('/personas/batch', [PersonaController::class, 'batch']);

    // Personals Client
    Route::get('personals', [PersonalController::class, 'index']);
    Route::get('personals/{personal}', [PersonalController::class, 'show']);
    Route::get('personals/personal-by-dni/{personal}', [PersonalController::class, 'showByDni']);
    Route::post('personals', [PersonalController::class, 'store']);
    Route::post('personals-importar-personal', [PersonalController::class, 'importarPersonal']);
    Route::put('personals/{personal}', [PersonalController::class, 'update']);
    Route::delete('personals/{personal}', [PersonalController::class, 'destroy']);
    Route::post('/personals/batch', [PersonaController::class, 'batch']);

    //Empresa
    Route::get('get-empresa-information', [EmpresaController::class, 'getEmpresa']);

    // Sedes Client
    Route::get('sedes', [SedeController::class, 'index']);
    Route::get('sedes/{sede}', [SedeController::class, 'show']);
    Route::get('sedes/sede-by-codigo/{sede}', [SedeController::class, 'showByCodigo']);
    Route::get('sedes-contar-contratos', [SedeController::class, 'contarPersonal']);
    Route::post('sedes', [SedeController::class, 'store']);
    Route::put('sedes/{sede}', [SedeController::class, 'update']);
    Route::delete('sedes/{sede}', [SedeController::class, 'destroy']);
    Route::post('/sedes/batch', [SedeController::class, 'batch']);

    // Oficinas Client
    Route::get('oficinas', [OficinaController::class, 'index']);
    Route::get('oficinas/{oficina}', [OficinaController::class, 'show']);
    Route::get('oficinas/oficina-by-codigo/{oficina}', [OficinaController::class, 'showByCodigo']);
    Route::get('oficinas-contar-personal', [OficinaController::class, 'contarPersonal']);
    Route::post('oficinas', [OficinaController::class, 'store']);
    Route::put('oficinas/{oficina}', [OficinaController::class, 'update']);
    Route::delete('oficinas/{oficina}', [OficinaController::class, 'destroy']);
    Route::post('/oficinas/batch', [OficinaController::class, 'batch']);

    // Proyectos Client
    Route::get('proyectos', [ProyectoController::class, 'index']);
    Route::get('proyectos/{proyecto}', [ProyectoController::class, 'show']);
    Route::get('proyectos-contar-personal', [ProyectoController::class, 'contarPersonal']);
    Route::post('proyectos', [ProyectoController::class, 'store']);
    Route::put('proyectos/{proyecto}', [ProyectoController::class, 'update']);
    Route::delete('proyectos/{proyecto}', [ProyectoController::class, 'destroy']);
    Route::post('/proyectos/batch', [ProyectoController::class, 'batch']);

    //Ubigeo
    Route::get('ubigeo', [UbigeoController::class, 'getUbigeo']);
    Route::get('departamentos', [UbigeoController::class, 'getDepartamentos']);
    Route::get('provincias', [UbigeoController::class, 'getProvincias']);
    Route::get('distritos', [UbigeoController::class, 'getDistritos']);
});
