import DashbordLayout from '@/layouts/DashbordLayout.vue';
import LoginPage from '@/pages/auth/LoginPage.vue';
import SSOLoginPage from '@/pages/auth/sso/SSOLoginPage.vue';
import DashboardPage from '@/pages/dashboard/DashboardPage.vue';
import PersonalsPage from '../pages/personals/PersonalsPage.vue';
import PersonasPage from '@/pages/personas/PersonasPage.vue';
import UsersPage from '@/pages/users/UsersPage.vue';
import UsersIdPage from '@/pages/users/UsersIdPage.vue'
import RolesPage from '@/pages/roles/RolesPage.vue';
import RolesIdPage from '@/pages/roles/RolesIdPage.vue';
import PermissionsPage from '@/pages/permissions/PermissionsPage.vue';
import EmpresaPage from '@/pages/empresas/EmpresaPage.vue';
import SistemasPage from '@/pages/sistemas/SistemasPage.vue';
import TenantsPage from '@/pages/tenants/TenantsPage.vue';
import CargosPage from '@/pages/cargos/CargosPage.vue';
import SedesPage from '../pages/sedes/SedesPage.vue';
import OficinasPage from '../pages/oficinas/OficinasPage.vue';
import ProyectosPage from '../pages/proyectos/ProyectosPage.vue';
import ServiciosPage from '../pages/servicios/ServiciosPage.vue';
import ServiciosTablePage from '@/pages/servicios/ServiciosTablePage.vue';
import ErrorNotFound from '@/pages/errors/ErrorNotFound.vue';
import ErrorUnauthorized from '@/pages/errors/ErrorUnauthorized.vue';
import ProfilePage from '@/pages/profile/ProfilePage.vue';
import ArchivosPage from '../pages/archivos/ArchivosPage.vue';

const routes = [
    {
        path: '/login',
        name: "login",
        component: LoginPage,
    },
    {
        path: '/sso/login',
        name: "ssoLogin",
        component: SSOLoginPage,
    },
    {
        path: '/',
        component: DashbordLayout,
        children: [
            { path: "/dashboard", name: "dashboard", component: DashboardPage },
            { path: "/profile", name: "profile", component: ProfilePage },

            //Accesos
            { path: "/usuarios", name: "usuarios", component: UsersPage, meta: { permission: 'access_users' } },
            { path: "/usuarios/:userId", name: "userId", component: UsersIdPage, props: true, meta: { permission: 'edit_users' } },
            { path: "/roles", name: "roles", component: RolesPage, meta: { permission: 'access_roles' } },
            { path: "/roles/:roleId", name: "roleId", component: RolesIdPage, props: true, meta: { permission: 'edit_roles' } },
            { path: "/permisos", name: "permisos", component: PermissionsPage, meta: { permission: 'access_permisos' } },

            //Servicios
            { path: "/servicios", name: "servicios", component: ServiciosPage },
            {
                path: "/servicios_table",
                name: "servicios_table",
                component: ServiciosTablePage,
                children: [
                    { path: "sistemas", name: "sistemas", component: SistemasPage, meta: { permission: 'access_sistemas' } },
                    { path: "tenants", name: "tenants", component: TenantsPage, meta: { permission: 'access_tenants' } },
                    { path: "personas", name: "personas", component: PersonasPage, meta: { permission: 'access_personas' } },
                    { path: "personals", name: "personals", component: PersonalsPage, meta: { permission: 'access_personals' } },
                    { path: "sedes", name: "sedes", component: SedesPage, meta: { permission: 'access_sedes' } },
                    { path: "oficinas", name: "oficinas", component: OficinasPage, meta: { permission: 'access_oficinas' } },
                    { path: "proyectos", name: "proyectos", component: ProyectosPage, meta: { permission: 'access_proyectos' } },
                    { path: "cargos", name: "cargos", component: CargosPage, meta: { permission: 'access_cargos' } },
                ]
            },
            { path: "/archivos", name: "archivos", component: ArchivosPage, meta: { permission: 'access_archivos' } },

            //Empresa
            { path: "/empresa", name: "empresa", component: EmpresaPage, meta: { permission: 'access_empresa' } },
        ]
    },

    {
        path: '/unauthorized',
        component: ErrorUnauthorized
    },

    {
        path: '/:catchAll(.*)*',
        component: ErrorNotFound
    }
]

export default routes
