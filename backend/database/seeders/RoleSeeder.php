<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Administrador
        $super_admin = Role::updateOrCreate(['name' => 'Super Administrador'], ['name' => 'Super Administrador', 'guard_name' => 'api']);

        //Dashboard
        Permission::updateOrCreate(
            ['name' => 'show_dashboard'],
            ['name' => 'show_dashboard', 'label' => 'Ver dashboard', 'module' => 'Dashboard', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Sistemas
        Permission::updateOrCreate(
            ['name' => 'access_sistemas'],
            ['name' => 'access_sistemas', 'label' => 'Acceder a Sistemas', 'module' => 'Sistemas', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'create_sistemas'],
            ['name' => 'create_sistemas', 'label' => 'Crear Sistemas', 'module' => 'Sistemas', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_sistemas'],
            ['name' => 'edit_sistemas', 'label' => 'Editar Sistemas', 'module' => 'Sistemas', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'delete_sistemas'],
            ['name' => 'delete_sistemas', 'label' => 'Eliminar Sistemas', 'module' => 'Sistemas', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Tenants
        Permission::updateOrCreate(
            ['name' => 'access_tenants'],
            ['name' => 'access_tenants', 'label' => 'Acceder a Tenants', 'module' => 'Tenants', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'create_tenants'],
            ['name' => 'create_tenants', 'label' => 'Crear Tenants', 'module' => 'Tenants', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_tenants'],
            ['name' => 'edit_tenants', 'label' => 'Editar Tenants', 'module' => 'Tenants', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'delete_tenants'],
            ['name' => 'delete_tenants', 'label' => 'Eliminar Tenants', 'module' => 'Tenants', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Cargos
        Permission::updateOrCreate(
            ['name' => 'access_cargos'],
            ['name' => 'access_cargos', 'label' => 'Acceder a Cargos', 'module' => 'Cargos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'create_cargos'],
            ['name' => 'create_cargos', 'label' => 'Crear Cargos', 'module' => 'Cargos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_cargos'],
            ['name' => 'edit_cargos', 'label' => 'Editar Cargos', 'module' => 'Cargos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'delete_cargos'],
            ['name' => 'delete_cargos', 'label' => 'Eliminar Cargos', 'module' => 'Cargos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Personals
        Permission::updateOrCreate(
            ['name' => 'access_personals'],
            ['name' => 'access_personals', 'label' => 'Acceder a Personal', 'module' => 'Personal', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'create_personals'],
            ['name' => 'create_personals', 'label' => 'Crear Personal', 'module' => 'Personal', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_personals'],
            ['name' => 'edit_personals', 'label' => 'Editar Personal', 'module' => 'Personal', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'delete_personals'],
            ['name' => 'delete_personals', 'label' => 'Eliminar Personal', 'module' => 'Personal', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Personas
        Permission::updateOrCreate(
            ['name' => 'access_personas'],
            ['name' => 'access_personas', 'label' => 'Acceder a Personas', 'module' => 'Personas', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'create_personas'],
            ['name' => 'create_personas', 'label' => 'Crear Personas', 'module' => 'Personas', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_personas'],
            ['name' => 'edit_personas', 'label' => 'Editar Personas', 'module' => 'Personas', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'delete_personas'],
            ['name' => 'delete_personas', 'label' => 'Eliminar Personas', 'module' => 'Personas', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Users
        Permission::updateOrCreate(
            ['name' => 'access_users'],
            ['name' => 'access_users', 'label' => 'Acceder a Usuarios', 'module' => 'Usuarios', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'create_users'],
            ['name' => 'create_users', 'label' => 'Crear Usuarios', 'module' => 'Usuarios', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_users'],
            ['name' => 'edit_users', 'label' => 'Editar Usuarios', 'module' => 'Usuarios', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'delete_users'],
            ['name' => 'delete_users', 'label' => 'Eliminar Usuarios', 'module' => 'Usuarios', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Roles
        Permission::updateOrCreate(
            ['name' => 'access_roles'],
            ['name' => 'access_roles', 'label' => 'Acceder a Roles', 'module' => 'Roles', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'create_roles'],
            ['name' => 'create_roles', 'label' => 'Crear Roles', 'module' => 'Roles', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_roles'],
            ['name' => 'edit_roles', 'label' => 'Editar Roles', 'module' => 'Roles', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'delete_roles'],
            ['name' => 'delete_roles', 'label' => 'Eliminar Roles', 'module' => 'Roles', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Permisos
        Permission::updateOrCreate(
            ['name' => 'access_permisos'],
            ['name' => 'access_permisos', 'label' => 'Acceder a Permisos', 'module' => 'Permisos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'create_permisos'],
            ['name' => 'create_permisos', 'label' => 'Crear Permisos', 'module' => 'Permisos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_permisos'],
            ['name' => 'edit_permisos', 'label' => 'Editar Permisos', 'module' => 'Permisos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'delete_permisos'],
            ['name' => 'delete_permisos', 'label' => 'Eliminar Permisos', 'module' => 'Permisos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Empresas
        Permission::updateOrCreate(
            ['name' => 'access_empresa'],
            ['name' => 'access_empresa', 'label' => 'Acceder a Empresa', 'module' => 'Empresa', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_empresa'],
            ['name' => 'edit_empresa', 'label' => 'Editar Empresa', 'module' => 'Empresa', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Sedes
        Permission::updateOrCreate(
            ['name' => 'access_sedes'],
            ['name' => 'access_sedes', 'label' => 'Acceder a Sedes', 'module' => 'Sedes', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'create_sedes'],
            ['name' => 'create_sedes', 'label' => 'Crear Sedes', 'module' => 'Sedes', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_sedes'],
            ['name' => 'edit_sedes', 'label' => 'Editar Sedes', 'module' => 'Sedes', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'delete_sedes'],
            ['name' => 'delete_sedes', 'label' => 'Eliminar Sedes', 'module' => 'Sedes', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Oficinas
        Permission::updateOrCreate(
            ['name' => 'access_oficinas'],
            ['name' => 'access_oficinas', 'label' => 'Acceder a Oficinas', 'module' => 'Oficinas', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'create_oficinas'],
            ['name' => 'create_oficinas', 'label' => 'Crear Oficinas', 'module' => 'Oficinas', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_oficinas'],
            ['name' => 'edit_oficinas', 'label' => 'Editar Oficinas', 'module' => 'Oficinas', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'delete_oficinas'],
            ['name' => 'delete_oficinas', 'label' => 'Eliminar Oficinas', 'module' => 'Oficinas', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Proyectos
        Permission::updateOrCreate(
            ['name' => 'access_proyectos'],
            ['name' => 'access_proyectos', 'label' => 'Acceder a Proyectos', 'module' => 'Proyectos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'create_proyectos'],
            ['name' => 'create_proyectos', 'label' => 'Crear Proyectos', 'module' => 'Proyectos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_proyectos'],
            ['name' => 'edit_proyectos', 'label' => 'Editar Proyectos', 'module' => 'Proyectos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'delete_proyectos'],
            ['name' => 'delete_proyectos', 'label' => 'Eliminar Proyectos', 'module' => 'Proyectos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);

        //Archivos
        Permission::updateOrCreate(
            ['name' => 'access_archivos'],
            ['name' => 'access_archivos', 'label' => 'Acceder a Archivos', 'module' => 'Archivos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'create_archivos'],
            ['name' => 'create_archivos', 'label' => 'Crear Archivos', 'module' => 'Archivos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'edit_archivos'],
            ['name' => 'edit_archivos', 'label' => 'Editar Archivos', 'module' => 'Archivos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
        Permission::updateOrCreate(
            ['name' => 'delete_archivos'],
            ['name' => 'delete_archivos', 'label' => 'Eliminar Archivos', 'module' => 'Archivos', 'guard_name' => 'api']
        )->syncRoles([$super_admin]);
    }
}
