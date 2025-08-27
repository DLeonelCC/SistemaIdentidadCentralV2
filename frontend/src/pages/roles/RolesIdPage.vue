<template>
    <Breadcrumb :home="home" :model="items" style="background-color: transparent;" >
        <template #item="{ item, props }">
            <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
                <a :href="href" v-bind="props.action" @click="navigate">
                    <span :class="[item.icon, 'text-color']" />
                    <span class="text-primary font-semibold">{{ item.label }}</span>
                </a>
            </router-link>
            <a v-else :href="item.url" :target="item.target" v-bind="props.action">
                <span class="text-surface-700 dark:text-surface-0">{{ item.label }}</span>
            </a>
        </template>
    </Breadcrumb>

    <div class="grid grid-cols-12 gap-4">
        <div class="col-span-12 md:col-span-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-4">Rol</h5>
                </div>

                <div class="card-body mb-4">
                    <RolesForm ref="elRolesForm" :hiddenAlert="true" />
                </div>

                <div class="card-footer">
                    <Button label="Guardar" class="p-button-primary" :loading="btnLoadings[0]" @click="save()" />
                </div>
            </div>
        </div>

        <!-- Columna Derecha -->
        <div class="col-span-12  md:col-span-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-4">Permisos</h5>
                    <Message severity="secondary" :closable="false" class="mb-4">
                        Al actualizar permisos, los usuarios que tengan asignado este Rol deberán cerrar sesión
                        e iniciar sesión nuevamente.
                    </Message>
                </div>

                <div class="card-body">
                    <div class="grid grid-cols-12 gap-x-4">
                        <div v-for="m in modules" :key="m.module" class="card col-span-12 md:col-span-4 lg:col-span-3">
                            <div class="px-4 py-2 font-semibold">
                                {{ m.module }}
                            </div>

                            <div class="p-4">
                                <table class="w-full border-collapse">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="flex flex-wrap mx-2">
                                                    <div v-for="p in permisos" :key="p.name" class="w-full">
                                                        <template v-if="m.module == p.module">
                                                            <div class="flex items-center">
                                                                <Checkbox v-model="permisosSelected" :inputId="p.name"
                                                                    :value="p.name" />
                                                                <label :for="p.name" class="ml-2">{{ p.label }}</label>
                                                            </div>
                                                        </template>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Toast />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth.js';
import { useToast } from 'primevue/usetoast';
import RolesService from '@/services/RolesService.js';
import RolesForm from './RolesForm.vue';

const authStore = useAuthStore();
const toast = useToast();

const props = defineProps({
    roleId: { type: String, default: null },
});

//Rutas
const home = ref({
    icon: 'pi pi-table',
    label: 'Roles',
    route: '/roles',
});
const items = ref([])

//Estados Reactivos
const row = ref({});
const modules = ref([]);
const permisos = ref([]);
const permisosSelected = ref([]);

const elRolesForm = ref(null);
const btnLoadings = ref([false]);

//OnMounted
onMounted(async () => {
    getItem();
});

//Métodos
async function getItem() {
    await RolesService.get(props.roleId).then(
        (response) => {
            row.value = response.role;
            modules.value = response.modules;
            permisos.value = response.permisos;
            permisosSelected.value = response.permisosSelected;

            items.value.push({ label: row.value.name })
        }
    );
    elRolesForm.value.setValue(row.value);
};

async function save() {
    btnLoadings.value[0] = true;
    try {
        await RolesService.save({
            id: elRolesForm.value.form.id,
            name: elRolesForm.value.form.name,
            sistema_id: elRolesForm.value.form.sistema_id,
            tenant_id: elRolesForm.value.form.tenant_id,
            permisosSelected: permisosSelected.value
        });
        toast.add({ severity: 'success', summary: 'Éxito', detail: `Operación exitosa`, life: 3000 });
        btnLoadings.value[0] = false;
    } catch (e) {
        console.log(e)
        btnLoadings.value[0] = false;
        elRolesForm.value.setErrors(e.response.data.errors);
    }
};

async function eliminar(id) {

};
</script>